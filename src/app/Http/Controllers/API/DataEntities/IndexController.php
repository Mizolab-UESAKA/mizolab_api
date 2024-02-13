<?php

namespace App\Http\Controllers\API\DataEntities;

use App\Http\Controllers\Controller;
use App\Http\Resources\DataEntities\Collection;
use App\Repositories\DataEntities\DataEntitiesRepository;
use App\Http\Requests\DataEntities\IndexRequest;

class IndexController extends Controller
{
    public function __invoke(
        IndexRequest $request,
        DataEntitiesRepository $data_entities_repository
    )
    {
        $params = $request->validated();
        // 1.データ取得
        // 1.1 repositoryからデータを取得する
        $data_entities = $data_entities_repository->getDataEntities($params);

        // 1.2 resourceでデータを整形する（collectionを渡す）
        $data_entities_resources = new Collection($data_entities);

        // 2.結果を返す
        return $data_entities_resources;
    }
}
