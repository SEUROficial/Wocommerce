<?php
/**
 * @category SeurTransporte
 * @package SeurTransporte/seur
 * @author Maria Jose Santos <mariajose.santos@ebolution.com>
 * @copyright 2023 Seur Transporte
 * @license https://seur.com/ Proprietary
 */
class PrinterType
{
    const PRINTER_TYPE_PDF = 'PDF';
    const PRINTER_TYPE_ETIQUETA = 'ZPL';
    const PRINTER_TYPE_A4_3 = 'A4_3';
    const TEMPLATE_TYPE_A4_3 = 'Z4_TWO_BODIES';

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::PRINTER_TYPE_PDF, 'label' => 'PDF'],
            ['value' => self::PRINTER_TYPE_ETIQUETA, 'label' => 'ZPL'],
            ['value' => self::PRINTER_TYPE_A4_3, 'label' => 'A4_3']
        ];
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        $options = [];
        foreach ($this->toOptionArray() as $option) {
            $options[$option['value']] = $option['label'];
        }
        return $options;
    }

}