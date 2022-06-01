<?php namespace App\Http\Controllers;

use App\Services\Envato\EnvatoApiClient;
use App\Tag;
use App\User;
use Auth;
use Common\Core\BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EnvatoController extends BaseController
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var EnvatoApiClient
     */
    private $envatoClient;

    public function __construct(Request $request, EnvatoApiClient $envatoClient)
    {
        $this->request = $request;
        $this->envatoClient = $envatoClient;
    }

    public function validateCode()
    {
        $code = $this->request->get('purchase_code');

        if ($purchase = $this->envatoClient->getPurchaseByCode($code)) {
            return $this->success([
                'valid' => !!$purchase,
                'code' => $purchase,
            ]);
        } else {
            return $this->error(__('This purchase code is not valid.'));
        }
    }

    public function addPurchaseUsingCode(): JsonResponse
    {
        $this->validate($this->request, [
            'purchaseCode' => 'required|string',
        ]);

        $envatoPurchase = $this->envatoClient->getPurchaseByCode(
            $this->request->get('purchaseCode'),
        );
        if (!$envatoPurchase) {
            return $this->error(__('Could not find purchase with that code.'));
        }

        Auth::user()->updatePurchases([$envatoPurchase]);
        $purchase = Auth::user()
            ->purchase_codes()
            ->first();

        return $this->success(['purchase' => $purchase]);
    }

    public function syncPurchases(User $user)
    {
        $this->authorize('update', $user);

        $purchases = $this->envatoClient->getBuyerPurchases($user->id);
        $user->updatePurchases($purchases);

        return $this->success(['purchases' => $purchases]);
    }

    public function importItems()
    {
        $names = $this->envatoClient->importAuthorItems();

        $items = collect($names)->map(function ($name) {
            $tag = Tag::firstOrNew(['name' => $name]);
            $tag->fill(['type' => 'category'])->save();
            return $tag;
        });

        return $this->success(['items' => $items]);
    }
}
