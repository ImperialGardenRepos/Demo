<?php

namespace ig\Datasource\Highload;

use Bitrix\Main\ArgumentException;
use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\SystemException;
use ig\Helpers\ArrayHelper;

class VirtualPageTable extends DataManager
{
    /**
     * @return string
     */
    public static function getTableName(): string
    {
        return VIRTUAL_PAGE_HL_BLOCK_TABLE;
    }

    /**
     * @return array
     * @throws SystemException
     */
    public static function getMap(): array
    {
        return [
            new IntegerField('ID',
                [
                    'primary' => true,
                    'autocomplete' => true,
                ]
            ),
            new StringField('UF_NAME'),
            new StringField('UF_URL'),
            new StringField('UF_H1'),
            new StringField('UF_TITLE'),
            new StringField('UF_DESCRIPTION'),
            new StringField('UF_KEYWORDS'),
            new StringField('UF_TEXT'),
            new StringField('UF_PARAMS'),
            new StringField('UF_REAL_PAGE'),
            new IntegerField('UF_ACTIVE'),
        ];
    }

    /**
     * @param $url
     * @return array
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public static function getByUrl($url): array
    {
        $pages = static::getList([
            'select' => [
                'UF_TEXT',
                'UF_DESCRIPTION',
                'UF_TITLE',
                'UF_H1',
                'UF_URL',
                'UF_PARAMS',
            ],
            'filter' => [
                '=UF_ACTIVE' => 1,
                'UF_URL' => $url,
            ],
        ]);

        $result = [];
        while ($page = $pages->fetch()) {
            $result[] = ArrayHelper::removeKeyPrefix($page);
        }
        return $result;
    }

    /**
     * @param $url
     * @return array|null
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public static function getFilterParams($url): ?array
    {
        $params = static::getList([
            'select' => [
                'UF_PARAMS',
            ],
            'filter' => [
                '=UF_ACTIVE' => 1,
                '=UF_URL' => $url,
            ],
        ])->fetch();

        if ($params === false) {
            return null;
        }
        if (isset($params['UF_PARAMS'])) {
            if ($params['UF_PARAMS'] === '') {
                return null;
            }
            parse_str($params['UF_PARAMS'], $params);
            if (!isset($params['F'])) {
                return null;
            }
            return $params['F'];
        }
        return null;
    }

    /**
     * @param $url
     * @return bool
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public static function getIsVirtualFilter($url): bool
    {
        return static::getFilterParams($url) !== null;
    }

    /**
     * @param array $filterParams
     * @return string|null
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public static function getByFilterParams(array $filterParams = []): ?string
    {
        if ($filterParams === []) {
            return null;
        }
        $query = [];
        foreach ($filterParams as $key => $filterParam) {
            if (!is_scalar($filterParam)) {
                /** ToDo: array values for virtual pages are not implemented */
                return null;
            }
            $query[] = urlencode("F[{$key}]") . "={$filterParam}";
        }
        $query[] = 'frmCatalogFilterSent=Y';
        $params = implode('&', $query);
        $result = static::getList([
            'select' => [
                'UF_URL',
            ],
            'filter' => [
                '=UF_PARAMS' => $params,
            ],
        ])->fetch();
        return $result['UF_URL'] ?? null;
    }
}