<?php
require_once _PS_MODULE_DIR_.'advancedimporter/classes/xmlimportflowsabstract.php';

class StockFlowsImport extends XmlImportFlowsAbstract
{
    /**
     * Get channel number block.
     */
    public function getChannel()
    {
        return 1;
    }

    /**
     * Get callback.
     */
    public function getCallback()
    {
        return 'StockImporter::exec';
    }

    public function isFileConcerned($file = null)
    {
        if (!parent::isFileConcerned($file)) {
            return false;
        }

        return (bool) count($this->last_xml_loaded->xpath('/stocks'));
    }

    public function translate($block)
    {
        $result = array();

        $this->parseDefaultFields($block, $result);

        if (isset($block->product['use-external-reference'])) {
            $result['product_external_reference'] = (string) $block->product;
        }

        if (isset($block->combination) && isset($block->combination['use-external-reference'])) {
            $result['combination_external_reference'] = (string) $block->combination;
        }

        $result['product'] = (int) (string) $block->product;

        if (!$this->isAttributeSet('ean13', $block)
            && !$this->isAttributeSet('reference', $block)
            && !$this->isAttributeSet('supplier_reference', $block)
        ) {
            if (!$this->isAttributeSet('product', $block) && !$this->isAttributeSet('combination', $block)) {
                throw new Exception('Product is missing');
            }

            $result['product'] = (int) (string) $block->product;
            $result['reference'] = 0;
            $result['ean13'] = 0;
        } elseif ($this->isAttributeSet('reference', $block)
            && $this->isAttributeSet('supplier_reference', $block)
        ) {
            throw new Exception('You cannot defined supplier_reference and reference');
        } elseif ($this->isAttributeSet('supplier_reference', $block)
            && $this->isAttributeSet('ean13', $block)
        ) {
            throw new Exception('You cannot defined ean13 and supplier_reference');
        } elseif ($this->isAttributeSet('reference', $block) && $this->isAttributeSet('ean13', $block)) {
            throw new Exception('You cannot defined ean13 and reference');
        } elseif ($this->isAttributeSet('reference', $block)) {
            if ($this->isAttributeSet('product', $block)) {
                throw new Exception('Product cannot be defined if reference is defined');
            }

            $result['product'] = 0;
            $result['reference'] = (string) $block->reference;
            $result['ean13'] = 0;
            $result['supplier_reference'] = 0;
        } elseif ($this->isAttributeSet('ean13', $block)) {
            if ($this->isAttributeSet('product', $block)) {
                throw new Exception('Product cannot be defined if ean13 is defined');
            }

            $result['product'] = 0;
            $result['ean13'] = (string) $block->ean13;
            $result['reference'] = 0;
            $result['supplier_reference'] = 0;
        } elseif ($this->isAttributeSet('supplier_reference', $block)) {
            if ($this->isAttributeSet('product', $block)) {
                throw new Exception('Product cannot be defined if supplier_reference is defined');
            }

            $result['product'] = 0;
            $result['supplier_reference'] = (string) $block->supplier_reference;
            $result['reference'] = 0;
            $result['ean13'] = 0;
        } else {
            throw new Exception('Product cannot be defined if product, reference, or ean13 is defined');
        }

        $result['combination'] = 0;
        if ($block->combination && (int) (string) $block->combination) {
            $result['combination'] = (int) (string) $block->combination;
        }

        $result['sellable_out_of_stock'] = 2;
        if ($block->sellable_out_of_stock) {
            $result['sellable_out_of_stock'] = (int) (string) $block->sellable_out_of_stock;
        }

        if (!$block->quantity) {
            throw new Exception('Quantity is missing');
        }

        if (!$block->mode) {
            $result['mode'] = 'set';
        } else {
            if (!in_array((string) $block->mode, array('set', 'delta'))) {
                throw new Exception('Mode must be "set" or "delta"');
            }

            $result['mode'] = (string) $block->mode;
        }

        if (!empty($block->warehouse)) {
            $result['warehouse'] = (int) (string) $block->warehouse;
        }
        if (!empty($block->price)) {
            $result['price'] = (int) (string) $block->price;
        }
        $result['reason'] = 1;
        if (isset($block->reason)) {
            $result['reason'] = (int) (string) $block->reason;
        }
        if (isset($block->location)) {
            $result['location'] = (string) $block->location;
        }
        if (!empty($block->currency)) {
            $result['currency'] = (int) (string) $block->currency;
        }
        $result['usable'] = 1;
        if (isset($block->usable)) {
            $result['usable'] = (bool) (string) $block->usable;
        }
        $result['employee'] = 1;
        if (isset($block->employee)) {
            $result['employee'] = (int) (string) $block->employee;
        }

        $result['quantity'] = (float) (string) $block->quantity;
		
        if ($block->template == 'Freidig') {
            if ($result['quantity'] == 3 )
            $result['quantity'] = 4;
        } 		
        
        if ($block->template == 'Hostettler') {
            if ($block->quantity == 'Sur demande' ){ 
            $result['quantity'] = 0;
            }
            if ($block->quantity == 'Disponible à court terme' ){
            $result['quantity'] = 0;
            }
            if ($block->quantity == 'Disponible dans le principal camp de Sursee' ){
            $result['quantity'] = 1;
            }
        }
		
        if ($block->template == 'Revit') {
            if ($block->quantity == '5+' ){ 
            $result['quantity'] = 6;
            }			
            if ($block->quantity == '10+' ){ 
            $result['quantity'] = 11;
            }
        }		
              
        return $result;
    }
}
