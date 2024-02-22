<?php

namespace InLineStudio\TheyWorkForYou\controllers;

use Craft;
use craft\web\Controller;
use yii\base\DynamicModel;
use craft\web\UploadedFile;
use League\Csv\Reader;
use InLineStudio\TheyWorkForYou\models\Contact as ContactModel;

class ContactController extends Controller
{
    /**
     * @var array<string> $csvHeader
     */
    protected array $csvHeader = [
        'Forename',
        'Surname',
        'Name (Display As)',
        'Name (List As)',
        'Party',
        'Constituency',
        'Email',
        'Address 1',
        'Address 2',
        'Postcode',
    ];

    public function actionIndex(): mixed
    {
        $contacts = ContactModel::find()->all();
        return $this->renderTemplate('they-work-for-you/contacts/_index', [
            'contacts' => $contacts,
        ]);
    }

    /**
     * perform the import
     */
    public function actionImport(): mixed
    {
        $this->requirePostRequest();
        $file = UploadedFile::getInstanceByName('file');

        $model = new DynamicModel([
            'file' => $file,
        ]);
        $model->addRule(['file'], 'required')
            ->validate();

        if (true === $model->hasErrors()) {
            // validation failed
            Craft::$app->getSession()->setNotice('An Error Occurred with the form submission.');

            Craft::$app->urlManager->setRouteParams([
                'errors' => $model->getErrors()
            ]);

            return null;
        }

        $reader = Reader::createFromPath($file->tempName, 'r');
        $reader->setHeaderOffset(0);
        $headers = $reader->getHeader();

        // validate we have all the correct headers
        foreach ($this->csvHeader as $expectedHeader) {
            if (!in_array($expectedHeader, $headers)) {
                Craft::$app->getSession()->setNotice("The CSV file is missing the required header: '{$expectedHeader}'.");
                return null;
            }

        }
        // truncate table
        Craft::$app->db->createCommand()->truncateTable(ContactModel::tableName())->execute();
        $records = $reader->getRecords();
        $totalRecords = 0;

        foreach ($records as $record) {
            $contact = new ContactModel();
            $contact->forename      = trim($record['Forename']);
            $contact->surname       = trim($record['Surname']);
            $contact->display_as    = trim($record['Name (Display As)']);
            $contact->list_as       = trim($record['Name (List As)']);
            $contact->party         = trim($record['Party']);
            $contact->constituency  = trim($record['Constituency']);
            $contact->email         = trim($record['Email']);
            $contact->address_1     = trim($record['Address 1']);
            $contact->address_2     = trim($record['Address 2']);
            $contact->postcode      = trim($record['Postcode']);
            $contact->save();
            $totalRecords++;
        }

        // validation failed
        Craft::$app->getSession()->setNotice("Successfully imported {$totalRecords} records");

        // return true;
        return $this->redirectToPostedUrl();
    }
}
