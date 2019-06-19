<?php
class CustomEntities
{
    const FORM_ID     = 10;
    const GENRE_ID    = 20;
    const FANDOM_ID   = 30;

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
}