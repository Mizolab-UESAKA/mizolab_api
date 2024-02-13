<?php

namespace App\Http\Controllers\API\Zentra;

use App\Http\Controllers\Controller;
use App\Http\Resources\Zentra\Collection;
use App\Repositories\Zentra\ZentraRepository;
use App\Http\Requests\Zentra\IndexRequest;

class IndexController extends Controller
{
    public function __invoke(
        IndexRequest $request,
        ZentraRepository $zentra_repository
    )
    {
        $params = $request->validated();
        // 1.データ取得
        // 1.1 repositoryからデータを取得する
        $zentra = $zentra_repository->getZentra($params);

        // 1.2 resourceでデータを整形する（collectionを渡す）
        $zentra_resources = new Collection($zentra);

        // 2.結果を返す
        return $zentra_resources;
    }
}
