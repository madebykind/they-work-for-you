<?php

namespace InLineStudio\TheyWorkForYou\controllers;

use Craft;
use craft\web\Controller;
use InLineStudio\TheyWorkForYou\models\Contact;
use InLineStudio\TheyWorkForYou\services\TheyWorkForYouClient;
use InLineStudio\TheyWorkForYou\TheyWorkForYou;
use \InLineStudio\TheyWorkForYou\models\Settings;
use yii\web\BadRequestHttpException;
use yii\web\Response;

class ApiController extends Controller
{
    private TheyWorkForYouClient $client;
    private Settings $settings;

    protected array|bool|int $allowAnonymous = true;

    public function __construct($id, $module, $config = [])
    {
        $this->settings = TheyWorkForYou::getInstance()->getSettings();
        $this->client = new TheyWorkForYouClient(
            $this->settings->apiUrl, 
            $this->settings->apiKey
        );

        parent::__construct($id, $module, $config);
    }

    /**
     * use this hook to disable CSRF validation as not needed for this controller
     */
    public function beforeAction($action): bool
    {
        $this->enableCsrfValidation = false;

        return parent::beforeAction($action);
    }

    public function actionGetContactDetails(): Response
    {
        $this->validateRequest();
        $forename = $this->request->get('forename');
        $surname = $this->request->get('surname');
        $party = $this->request->get('party');
        $constituency = $this->request->get('constituency');

        $contactDetailsQuery = Contact::find();
        $whereClause = [];
        if (null !== $forename) {
            $whereClause['forename'] = $forename;
        }

        if (null !== $surname) {
            $whereClause['surname'] = $surname;
        }

        if (null !== $party) {
            $whereClause['party'] = $party;
        }

        if (null !== $constituency) {
            $whereClause['constituency'] = $constituency;
        }

        // do we have a where clause?
        if (count($whereClause) > 0) {
            $contactDetailsQuery = $contactDetailsQuery
                ->where($whereClause);
        }

        $results = $contactDetailsQuery
            ->all();
        return $this->asJson($results);
    }

    /**
     * relays the request to the relevant They Work For You API endpoint
     */
    public function actionGetQuota(): Response
    {
        $this->validateRequest();
        $quota = $this->client->get('getQuota');
        return $this->asJson($quota);
    }

    /**
     * relays the request to the relevant They Work For You API endpoint
     */
    public function actionConvertUrl(): Response
    {
        $this->validateRequest();

        $url = $this->request->get('url');
        if (null === $url) {
            throw new BadRequestHttpException('URL is required');
        }

        if(false === filter_var($url, FILTER_VALIDATE_URL)) {
            throw new BadRequestHttpException('URL must be a valid URL');
        }

        $convertedUrl = $this->client->get('convertURL', [
            'url' => $url,
        ]);

        return $this->asJson($convertedUrl);
    }

    /**
     * relays the request to the relevant They Work For You API endpoint
     */
    public function actionGetConstituency(): Response
    {
        $this->validateRequest();

        $name = $this->request->get('name');
        $postcode = $this->request->get('postcode');
        $constituency = $this->client->get('getConstituency', [
            'name' => $name,
            'postcode' => $postcode,
        ]);
        return $this->asJson($constituency);
    }

    /**
     * relays the request to the relevant They Work For You API endpoint
     */
    public function actionGetConstituencies(): Response
    {
        $this->validateRequest();
        //'date', 'search', 'latitude', 'longitude', 'distance'
        $date = $this->request->get('date');
        $search = $this->request->get('search');
        $latitude = $this->request->get('latitude');
        $longitude = $this->request->get('longitude');
        $distance = $this->request->get('distance');

        $constituency = $this->client->get('getConstituencies', [
            'date' => $date,
            'search' => $search,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'distance' => $distance,
        ]);
        return $this->asJson($constituency);
    }

    /**
     * relays the request to the relevant They Work For You API endpoint
     */
    public function actionGetPerson(): Response
    {
        $this->validateRequest();
        $id = $this->request->get('id');
        $person = $this->client->get('getPerson', [
            'id' => $id,
        ]);
        return $this->asJson($person);
    }

    /**
     * relays the request to the relevant They Work For You API endpoint
     */
    public function actionGetMp(): Response
    {
        $this->validateRequest();
        $id = $this->request->get('id');
        $constituency = $this->request->get('constituency');
        $postcode = $this->request->get('postcode');
        $alwaysReturn = $this->request->get('always_return');
        $mp = $this->client->get('getMP', [
            'id' => $id,
            'constituency' => $constituency,
            'postcode' => $postcode,
            'always_return' => $alwaysReturn,
        ]);
        return $this->asJson($mp);
    }

    /**
     * relays the request to the relevant They Work For You API endpoint
     */
    public function actionGetMpInfo(): Response
    {
        $this->validateRequest();
        $id = $this->request->get('id');
        $mpInfo = $this->client->get('getMPInfo', [
            'id' => $id,
        ]);
        return $this->asJson($mpInfo);
    }

    /**
     * relays the request to the relevant They Work For You API endpoint
     */
    public function actionGetMpsInfo(): Response
    {
        $this->validateRequest();
        $id = $this->request->get('id');
        $mpInfo = $this->client->get('getMPsInfo', [
            'id' => $id,
        ]);
        return $this->asJson($mpInfo);
    }

    /**
     * relays the request to the relevant They Work For You API endpoint
     */
    public function actionGetMps(): Response
    {
        $this->validateRequest();
        $date = $this->request->get('date');
        $party = $this->request->get('party');
        $search = $this->request->get('search');
        $mps = $this->client->get('getMPs', [
            'date' => $date,
            'party' => $party,
            'search' => $search,
        ]);
        return $this->asJson($mps);
    }

    /**
     * relays the request to the relevant They Work For You API endpoint
     */
    public function actionGetLord(): Response
    {
        $this->validateRequest();
        $id = $this->request->get('id');
        $lord = $this->client->get('getLord', [
            'id' => $id,
        ]);
        return $this->asJson($lord);
    }

    /**
     * relays the request to the relevant They Work For You API endpoint
     */
    public function actionGetLords(): Response
    {
        $this->validateRequest();
        $date = $this->request->get('date');
        $party = $this->request->get('party');
        $search = $this->request->get('search');
        $lords = $this->client->get('getLords', [
            'date' => $date,
            'party' => $party,
            'search' => $search,
        ]);
        return $this->asJson($lords);
    }

    /**
     * relays the request to the relevant They Work For You API endpoint
     */
    public function actionGetMla(): Response
    {
        $this->validateRequest();
        $id = $this->request->get('id');
        $constituency = $this->request->get('constituency');
        $postcode = $this->request->get('postcode');
        $alwaysReturn = $this->request->get('always_return');
        $mla = $this->client->get('getMLA', [
            'id' => $id,
            'constituency' => $constituency,
            'postcode' => $postcode,
            'always_return' => $alwaysReturn,
        ]);
        return $this->asJson($mla);
    }

    /**
     * relays the request to the relevant They Work For You API endpoint
     */
    public function actionGetMlas(): Response
    {
        $this->validateRequest();
        $date = $this->request->get('date');
        $party = $this->request->get('party');
        $search = $this->request->get('search');
        $mlas = $this->client->get('getMLAs', [
            'date' => $date,
            'party' => $party,
            'search' => $search,
        ]);
        return $this->asJson($mlas);
    }

    /**
     * relays the request to the relevant They Work For You API endpoint
     */
    public function actionGetMsp(): Response
    {
        $this->validateRequest();
        $id = $this->request->get('id');
        $constituency = $this->request->get('constituency');
        $postcode = $this->request->get('postcode');
        $alwaysReturn = $this->request->get('always_return');
        $msp = $this->client->get('getMSP', [
            'id' => $id,
            'constituency' => $constituency,
            'postcode' => $postcode,
            'always_return' => $alwaysReturn,
        ]);
        return $this->asJson($msp);
    }

    /**
     * relays the request to the relevant They Work For You API endpoint
     */
    public function actionGetMsps(): Response
    {
        $this->validateRequest();
        $date = $this->request->get('date');
        $party = $this->request->get('party');
        $search = $this->request->get('search');
        $msp = $this->client->get('getMSPs', [
            'date' => $date,
            'party' => $party,
            'search' => $search,
        ]);
        return $this->asJson($msp);
    }

    /**
     * relays the request to the relevant They Work For You API endpoint
     */
    public function actionGetGeometry(): Response
    {
        $this->validateRequest();
        $name = $this->request->get('name');
        $geometry = $this->client->get('getGeometry', [
            'name' => $name,
        ]);
        return $this->asJson($geometry);
    }

    /**
     * relays the request to the relevant They Work For You API endpoint
     */
    public function actionGetBoundary(): Response
    {
        $this->validateRequest();
        $name = $this->request->get('name');
        $boundary = $this->client->get('getBoundary', [
            'name' => $name,
        ]);
        return $this->asJson($boundary);
    }

    /**
     * relays the request to the relevant They Work For You API endpoint
     */
    public function actionGetCommittee(): Response
    {
        $this->validateRequest();
        $name = $this->request->get('name');
        $date = $this->request->get('date');
        $committee = $this->client->get('getCommittee', [
            'name' => $name,
            'date' => $date,
        ]);
        return $this->asJson($committee);
    }

    /**
     * relays the request to the relevant They Work For You API endpoint
     */
    public function actionGetDebates(): Response
    {
        $this->validateRequest();
        $type = $this->request->get('type');
        $date = $this->request->get('date');
        $search = $this->request->get('search');
        $person = $this->request->get('person');
        $gid = $this->request->get('gid');
        $order = $this->request->get('order');
        $page = $this->request->get('page');
        $num = $this->request->get('num');
        $debates = $this->client->get('getDebates', [
            'type' => $type,
            'date' => $date,
            'search' => $search,
            'person' => $person,
            'gid' => $gid,
            'order' => $order,
            'page' => $page,
            'num' => $num,
        ]);
        return $this->asJson($debates);
    }

    /**
     * relays the request to the relevant They Work For You API endpoint
     */
    public function actionGetWrans(): Response
    {
        $this->validateRequest();
        $date = $this->request->get('date');
        $search = $this->request->get('search');
        $person = $this->request->get('person');
        $gid = $this->request->get('gid');
        $order = $this->request->get('order');
        $page = $this->request->get('page');
        $num = $this->request->get('num');
        $wrans = $this->client->get('getWrans', [
            'date' => $date,
            'search' => $search,
            'person' => $person,
            'gid' => $gid,
            'order' => $order,
            'page' => $page,
            'num' => $num,
        ]);
        return $this->asJson($wrans);
    }

    /**
     * relays the request to the relevant They Work For You API endpoint
     */
    public function actionGetWms(): Response
    {
        $this->validateRequest();
        $date = $this->request->get('date');
        $search = $this->request->get('search');
        $person = $this->request->get('person');
        $gid = $this->request->get('gid');
        $order = $this->request->get('order');
        $page = $this->request->get('page');
        $num = $this->request->get('num');
        $wms = $this->client->get('getWMS', [
            'date' => $date,
            'search' => $search,
            'person' => $person,
            'gid' => $gid,
            'order' => $order,
            'page' => $page,
            'num' => $num,
        ]);
        return $this->asJson($wms);
    }

    /**
     * relays the request to the relevant They Work For You API endpoint
     */
    public function actionGetHansard(): Response
    {
        $this->validateRequest();
        $search = $this->request->get('search');
        $person = $this->request->get('person');
        $order = $this->request->get('order');
        $page = $this->request->get('page');
        $num = $this->request->get('num');
        $hansard = $this->client->get('getHansard', [
            'search' => $search,
            'person' => $person,
            'order' => $order,
            'page' => $page,
            'num' => $num,
        ]);
        return $this->asJson($hansard);
    }

    /**
     * relays the request to the relevant They Work For You API endpoint
     */
    public function actionGetComments(): Response
    {
        $this->validateRequest();
        $startDate = $this->request->get('start_date');
        $endDate = $this->request->get('end_date');
        $pid = $this->request->get('pid');
        $page = $this->request->get('page');
        $num = $this->request->get('num');
        $comments = $this->client->get('getComments', [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'pid' => $pid,
            'page' => $page,
            'num' => $num,
        ]);
        return $this->asJson($comments);
    }

    /**
     * common request validation
     */
    protected function validateRequest(): void
    {
        // $this->requirePostRequest();
        $this->requireAcceptsJson();
    }
}