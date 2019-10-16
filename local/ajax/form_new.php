<?php
require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Context;
use Bitrix\Main\Loader;

if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) || $_SERVER['HTTP_X_REQUESTED_WITH'] !== 'XMLHttpRequest') {
    $return = ['status' => 'right_error'];
    echo json_encode($return);
    die();
}

define('NO_KEEP_STATISTIC', true);
$return = [
    'result' => 'error'
];

if (check_bitrix_sessid()) {

    $request = Context::getCurrent()->getRequest()->toArray();

    $formId = $request['WEB_FORM_ID'];
    unset($request['WEB_FORM_ID'], $request['sessid']);

    Loader::includeModule('form');
    $fieldModel = new CFormField();
    $answerModel = new CFormAnswer();
    $resultModel = new CFormResult();
    $fieldResource = $fieldModel->GetList(
        $formId,
        'N',
        $by,
        $order,
        ['SID' => array_keys($request)],
        $isFiltered
    );

    $resultArray = [];
    while ($fieldRow = $fieldResource->Fetch()) {
        $answer = $answerModel->GetList($fieldRow['ID'], $by, $order, [], $isFiltered)->Fetch();
        $resultArray['form_' . $answer['FIELD_TYPE'] . '_' . $answer['ID']] = $request[$fieldRow['SID']];
    }

    $result = $resultModel->Add($formId, $resultArray);
    if ($result > 0) {
        $response = array_key_exists('name', $request) ? $request['name'] . ', спасибо!' : 'Спасибо!';
        $return = [
            'result' => 'success',
            'string' => '
            <div class="form__items js-message js-message-container active">
                <div class="form__message text-align-center">
                    <div class="heading-icon color-active">
                        <svg class="icon"><use xlink:href="/local/templates/ig/build/svg/symbol/svg/sprite.symbol.svg#icon-tick-in-circle"></use></svg>
                    </div>
                    <h1 class="h2">' . $response . '</h1>
                    <p>Ваше обращение успешно отправлено.</p>
                </div>
            </div>
            '
        ];
    } else {
        global $strError;
        $return['error'] = $strError;
    }
}
/*----------------------end main body code----------------------*/
echo json_encode($return);