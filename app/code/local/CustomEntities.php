<?php

/**
 * Class CustomEntities
 */
class CustomEntities
{
    const FORM_ID     = 3;
    const GENRE_ID    = 4;
    const FANDOM_ID   = 5;

    const FORM_NAME   = 'Form';
    const GENRE_NAME  = 'Genre';
    const FANDOM_NAME = 'Fandom';

    const FORM_URL    = 'form';
    const GENRE_URL   = 'genre';
    const FANDOM_URL  = 'fandom';

    protected static $_attributesBunch = [
        self::FORM_ID => [
            'id'       => self::FORM_ID,
            'name'     => self::FORM_NAME,
            'url_key'  => self::FORM_URL
        ],
        self::GENRE_ID => [
            'id'       => self::GENRE_ID,
            'name'     => self::GENRE_NAME,
            'url_key'  => self::GENRE_URL
        ],
        self::FANDOM_ID => [
            'id'       => self::FANDOM_ID,
            'name'     => self::FANDOM_NAME,
            'url_key'  => self::FANDOM_URL
        ],
    ];

    /**
     * @return array
     */
    public static function getAttributesBunch()
    {
        return static::$_attributesBunch;
    }

    /**
     * @param $categoryId
     * @return array|mixed
     */
    public static function getAttributesBunchForCategory($categoryId)
    {
        return isset(static::$_attributesBunch[$categoryId]) ? static::$_attributesBunch[$categoryId] : [];
    }

    /**
     * @param $name
     * @return mixed|null
     */
    public static function getCategoryIdByName($name)
    {
        $mapping = [
            static::FORM_NAME   => static::FORM_ID,
            static::GENRE_NAME  => static::GENRE_ID,
            static::FANDOM_NAME => static::FANDOM_ID
        ];

        return isset($mapping[$name]) ? $mapping[$name] : null;
    }

    public static function getUrlById($id)
    {
        return static::$_attributesBunch[$id]['url_key'];
    }

    /**
     * @return Mage_Core_Helper_Abstract|Figures_Catalog_Helper_CustomEntities
     */
    public static function helper()
    {
        return Mage::helper('figures_catalog/customEntities');
    }
}