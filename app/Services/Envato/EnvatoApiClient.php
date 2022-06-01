<?php namespace App\Services\Envato;

use App\User;
use Arr;
use Cache;
use Carbon\Carbon;
use Common\Auth\SocialProfile;
use Common\Core\HttpClient;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Collection;
use Str;

class EnvatoApiClient
{
    /**
     * @var Client
     */
    private $http;

    public function __construct(HttpClient $http)
    {
        $this->http = $http;
    }

    public function getBuyerPurchasesByToken(string $token): Collection
    {
        $r = $this->call('buyer/purchases', [], 'v3', $token);
        return collect($r['purchases'])
            ->filter(function (array $purchase) {
                // skip support extensions
                return !is_null($purchase['license']);
            })
            ->map(function (array $purchase) use ($r) {
                return $this->transformPurchaseData(
                    $purchase,
                    $r['buyer']['username'],
                );
            });
    }

    public function getBuyerPurchases(int $userId): ?Collection
    {
        $user = User::with('social_profiles')->find($userId);
        /** @var SocialProfile $profile */
        $profile = $user->social_profiles
            ->where('service_name', 'envato')
            ->first();

        if (!$profile) {
            return null;
        }

        if (
            !$profile->access_expires_at ||
            $profile->access_expires_at->lessThan(now())
        ) {
            $r = $this->http->post('https://api.envato.com/token', [
                'form_params' => [
                    'grant_type' => 'refresh_token',
                    'client_id' => config('services.envato.client_id'),
                    'client_secret' => config('services.envato.client_secret'),
                    'refresh_token' => $profile->refresh_token,
                ],
            ]);
            $profile
                ->fill([
                    'access_token' => $r['access_token'],
                    'access_expires_in' => Carbon::now()->addSeconds(
                        $r['expires_in'],
                    ),
                ])
                ->save();
        }

        return $this->getBuyerPurchasesByToken($profile->access_token);
    }

    public function getPurchaseByCode(string $code): ?array
    {
        if (!$code) {
            return null;
        }

        return Cache::remember(
            "purchase.$code",
            Carbon::now()->addMinutes(10),
            function () use ($code) {
                try {
                    $response = $this->call('author/sale', ['code' => $code]);
                } catch (ClientException $e) {
                    return null;
                }
                if (!isset($response['item'])) {
                    return null;
                }
                $data = $this->transformPurchaseData($response);
                $data['code'] = $code;
                return $data;
            },
        );
    }

    public function purchaseCodeIsValid(string $code): bool
    {
        return !!$this->getPurchaseByCode($code);
    }

    public function importAuthorItems(): array
    {
        $response = $this->call('market/private/user/username.json', [], 'v1');
        $response = $this->call(
            'discovery/search/search/item',
            ['username' => $response['username']],
            'v1',
        );

        return array_map(function ($item) {
            return $item['name'];
        }, $response['matches']);
    }

    public function call(
        string $uri,
        array $params = [],
        string $version = 'v3',
        string $token = null
    ): array {
        if ($version === 'v3') {
            $base = 'https://api.envato.com/v3/market/';
        } else {
            $base = 'https://api.envato.com/v1/';
        }

        $token = $token ?? config('services.envato.personal_token');
        $response = $this->http->get("{$base}{$uri}", [
            'headers' => [
                'Authorization' => "Bearer $token",
            ],
            'query' => $params,
        ]);

        return $response ?: [];
    }

    private function transformPurchaseData(
        array $data,
        string $envatoUsername = null
    ): array {
        return [
            'item_name' => $data['item']['name'],
            'item_id' => $data['item']['id'],
            'code' => Arr::get($data, 'code'),
            'purchased_at' => Arr::get($data, 'sold_at')
                ? Carbon::parse(Arr::get($data, 'sold_at'))
                : null,
            'supported_until' => $this->getSupportedUntilDate($data),
            'url' => Arr::get($data, 'item.url'),
            'image' => Arr::get($data, 'item.previews.icon_preview.icon_url'),
            'envato_username' => $envatoUsername ?? Arr::get($data, 'buyer'),
        ];
    }

    private function getSupportedUntilDate(array $data): ?Carbon
    {
        if ($date = Arr::get($data, 'supported_until')) {
            return Carbon::parse($date);
        }
        // TODO: adding 30 days temporarily for mobile app category as it's not supported by default on codecanyon
        if (
            Str::startsWith(
                Arr::get($data, 'item.classification') ?? '',
                'mobile',
            )
        ) {
            return Carbon::now()->addDays(30);
        }
        return null;
    }
}
