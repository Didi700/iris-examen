<?php

namespace App\Helpers;

class PaginationHelper
{
    /**
     * Obtenir le nombre d'éléments par page selon l'écran
     */
    public static function getPerPage($default = 10)
    {
        $perPage = request()->input('per_page', $default);
        
        // Limiter entre 5 et 100
        return min(max((int)$perPage, 5), 100);
    }

    /**
     * Options de pagination pour les select
     */
    public static function getPerPageOptions()
    {
        return [10, 25, 50, 100];
    }

    /**
     * Créer le HTML du sélecteur de pagination
     */
    public static function renderPerPageSelector()
    {
        $options = self::getPerPageOptions();
        $current = self::getPerPage();
        
        $html = '<select onchange="window.location.href=\'?per_page=\' + this.value" 
                        class="px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg">';
        
        foreach ($options as $option) {
            $selected = $option === $current ? 'selected' : '';
            $html .= "<option value=\"{$option}\" {$selected}>{$option} par page</option>";
        }
        
        $html .= '</select>';
        
        return $html;
    }
}