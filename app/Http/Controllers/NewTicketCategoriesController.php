<?php namespace App\Http\Controllers;

use App\Product;
use App\PurchaseCode;
use App\Services\Envato\EnvatoApiClient;
use App\Tag;
use Auth;
use Common\Core\BaseController;
use Common\Settings\Settings;
use Illuminate\Support\Collection;

class NewTicketCategoriesController extends BaseController
{
    /**
     * @var Settings
     */
    private $settings;

    public function __construct(Settings $settings)
    {
        $this->settings = $settings;

        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();

        $tags = Tag::with('categories')
            ->where('type', 'category')
            ->limit(25)
            ->orderBy('name', 'asc')
            ->get();

        return $this->success([
            'tags' => $this->filterCategoriesByPurchases($tags),
        ]);
    }

    /**
     * Filter specified tags by current user envato purchases.
     *
     * @param Tag[]|Collection $tags
     * @return Collection
     */
    private function filterCategoriesByPurchases($tags)
    {
        $user = Auth::user();

        $requireCode =
            $this->settings->get('envato.enable') &&
            $this->settings->get('envato.require_purchase_code');

        if (!$requireCode || $user->isAgent()) {
            return $tags;
        }

        $latestCode = $user->purchase_codes->first();
        if (
            !$user->isAgent() &&
            (!$latestCode || $latestCode->created_at->lt(now()->subMinutes(10)))
        ) {
            $purchases = app(EnvatoApiClient::class)->getBuyerPurchases(
                $user->id,
            );
            $user->updatePurchases($purchases);
        }

        $userPurchases = $user->purchase_codes->keyBy(function (
            PurchaseCode $code
        ) {
            return slugify($code->item_name);
        });

        $filteredTags = $tags->filter(function (Tag $tag) use ($userPurchases) {
            return $userPurchases->has($tag->name);
        });

        if (
            $this->settings->get('envato.active_support') &&
            !$user->isAgent()
        ) {
            $filteredTags->map(function ($tag) use ($userPurchases) {
                $supportedUntil = $userPurchases->get($tag->name)
                    ->supported_until;
                $tag['support_expired'] = $supportedUntil
                    ? $supportedUntil->lt(now())
                    : true;
                return $tag;
            });
        }
        return $filteredTags->values();
    }
}
