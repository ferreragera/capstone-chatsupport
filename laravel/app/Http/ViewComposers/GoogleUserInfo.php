<?php

namespace App\Http\ViewComposers;

use App\Models\GoogleUserInfo as GoogleUserInfoModel;
use Illuminate\Contracts\View\View;

class GoogleUserInfo {

    public function compose(View $view) {
        $authUser = auth()->user();
        if($authUser) {
            $googleUserInfo = GoogleUserInfoModel::where('email', $authUser->email)->first();

            $view->with('googleUserInfo', $googleUserInfo);
        }
    }
}