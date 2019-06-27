<?php

class Figures_Dashboard_Block_Dashboard_Design extends Figures_Dashboard_Block_Dashboard
{
    public function getFormCategories()
    {
        $formCategories = CustomEntities::helper()->getFormCategories();
        $formCategoriesMinimized = [];

        foreach ($formCategories as $formCategory) {
            $formCategoriesMinimized[] = [
                'name' => $formCategory->getName(),
                'id'   => $formCategory->getId()
            ];
        }

        return $formCategoriesMinimized;
    }

}