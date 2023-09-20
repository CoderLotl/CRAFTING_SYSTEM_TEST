<?php
namespace Model\Utilities;
use Model\Classes\Item;

class ObjectReviewer
{
    /**
     * Checks if the item type exists. Returns false if not, and the array with the attributes if
     * it exists.
     * @param string $itemType
     * 
     * @return false|array
     */
    public static function Glossary(string $itemType)
    {
        $items = array_keys(ITEMS);
        if(in_array($itemType, $items))
        {
            return ITEMS[$itemType];
        }
        return false;
    }

    /**
     * Checks if the item type is valid and if the attribute is valid.
     * @param string $itemType
     * @param string $attribute
     * 
     * @return bool
     */
    public static function ValidateAttribute(string $itemType, string $attribute)
    {
        $validAttributes = ObjectReviewer::Glossary($itemType);

        if($validAttributes !== false) //check if the item type is valid by getting its attributes
        {
            if(in_array($attribute, $validAttributes))
            {
                return true;
            }
        }
        return false;
    }

    public static function ValidateAttributes(string $itemType, array $attributes)
    {
        $validAttributes = ObjectReviewer::Glossary($itemType);

        foreach($attributes as $attribute)
        {
            if(!in_array($attribute, $validAttributes))
            {
                return false;
            }
        }
        return true;
    }

    /**
     * Receives an Item and a string defining the type. Checks the Item's properties against the ones defined at itemProperties.php on load.
     * Returns the item reviewed and corrected.
     * @param Item $item
     * @param string $itemType
     * 
     * @return Item|false
     */
    public static function ObjectReview(Item $item)
    {        
        $itemProperties = ObjectReviewer::Glossary($item->attributes->itemType);

        if($itemProperties !== false)
        {
            foreach($itemProperties as $key)
            {
                if(!isset($item->attributes[$key]))
                {
                    $item->attributes[$key] = 'null';
                }
            }
    
            foreach($item->attributes as $key => $value)
            {
                if(!in_array($key, $itemProperties))
                {
                    unset($item->attributes[$key]);
                }
            }
    
            return $item;
        }
        else
        {
            return false;
        }
    }
}