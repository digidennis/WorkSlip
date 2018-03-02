<?php



class Digidennis_WorkSlip_Model_Workslip_Pdf extends Mage_Sales_Model_Order_Pdf_Abstract
{
    /**
     * Return PDF document
     *
     * @param  array $invoices
     * @return Zend_Pdf
     * @throws Zend_Pdf_Exception
     */
    public function getPdf($workslips = array())
    {
        $this->_beforeGetPdf();
        $this->_initRenderer('shipment');
        $pdf = new Zend_Pdf();
        $this->_setPdf($pdf);
        $style = new Zend_Pdf_Style();
        $this->_setFontBold($style, 10);

        foreach ($workslips as $workslip) {
            $page = $this->newPage();
            $this->_setFontRegular($page, 12);

            //NUMBER & DATE
            $this->y = 779;
            $page->drawText($workslip->getWorkslipId(), 147.36,$this->y, 'UTF-8' );
            $page->drawText(Mage::helper('core')->formatDate($workslip->getCreateDate(), 'long', false), 147.36,$this->y=761.42, 'UTF-8' );

            //TOPINFO
            $leftstop = 110;
            $page->drawText($workslip->getName(), $leftstop+15, $this->y=700,'UTF-8' );
            $page->drawText($workslip->getEmail(), $leftstop+15, $this->y -= 30.5, 'UTF-8' );
            $page->drawText($workslip->getAddress(), $leftstop+15, $this->y -= 30.5, 'UTF-8' );
            $page->drawText($workslip->getZip(), $leftstop+15, $this->y -= 30.5, 'UTF-8' );
            $page->drawText($workslip->getCity(), $leftstop+48, $this->y, 'UTF-8' );
            $page->drawText($workslip->getPhone(), $leftstop+15, $this->y-=30.5, 'UTF-8' );
            $page->drawText(Mage::helper('core')->formatDate($workslip->getEstimateddoneDate(), 'long', false), $leftstop+280, $this->y, 'UTF-8' );
            $page->drawText(Mage::helper('core')->currency($workslip->getOfferPrice(), true, false), $leftstop+280, $this->y + 30.5, 'UTF-8' );

            //THEWORK
            $thework = preg_split('/\n|\r\n?/', $workslip->getWhattodo());
            $this->y = 520;
            $leftstop = 80;
            foreach ( $thework as $workline ){
                $splitlines = Mage::helper('core/string')->str_split($workline,70, true);
                foreach ($splitlines as $finalline)
                    $page->drawText($finalline, $leftstop, $this->y -= 30.5, 'UTF-8' );
            }

            //MATERIALS
            $materials = Mage::getModel('digidennis_workslip/material')
                ->getCollection()
                ->getWorkslipMaterials($workslip->getWorkslipId());
            $this->y = 280;
            foreach ($materials as $material ) {
                $splitlines = Mage::helper('core/string')->str_split($material->getDescription(),70, true);
                foreach ($splitlines as $finalline)
                    $page->drawText($finalline, $leftstop, $this->y -= 30.5, 'UTF-8' );
            }

            $mediafiles = unserialize($workslip->getMediafiles());
            $path = Mage::getBaseDir('media') . DS . 'uploads' . DS;
            foreach ($mediafiles as $mediafile ){
                $mimetype = mime_content_type($path . $mediafile['path'] );
                $this->_insertImageMedia($path . $mediafile['path'], $workslip->getWorkslipId());
            }
        }
        $this->_afterGetPdf();
        return $pdf;
    }

    public function newPage(array $settings = array())
    {
        /* Add new table head */
        $extractor = new Zend_Pdf_Resource_Extractor();
        $template = Zend_Pdf::load('skin/frontend/kbhskum/default/images/pdfskabelon-arbejdsseddel.pdf');
        $page = $extractor->clonePage( $template->pages[0]);
        $this->_getPdf()->pages[] = $page;
        $this->y = 700;
        return $page;
    }
    /**
     * Set font as regular
     */
    protected function _setFontRegular($object, $size = 7)
    {
        $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
        $object->setFont($font, $size);
        return $font;
    }
    /**
     * Set font as bold
     */
    protected function _setFontBold($object, $size = 7)
    {
        $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD);
        //$font = Zend_Pdf_Font::fontWithPath(Mage::getBaseDir() . '/lib/sitedesignfonts/Hind-Medium.ttf');
        $object->setFont($font, $size);
        return $font;
    }
    /**
     * Set font as italic
     */
    protected function _setFontItalic($object, $size = 7)
    {
        $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_ITALIC);
        $object->setFont($font, $size);
        return $font;
    }

    protected  function _insertImageMedia($file, $workslipid )
    {
        $newpage = new Zend_Pdf_Page( Zend_Pdf_Page::SIZE_A4_LANDSCAPE);
        $image = Zend_Pdf_Image::imageWithPath($file);
        $widthscale = 822.0 / $image->getPixelWidth();
        $heightscale = 575.0 / $image->getPixelHeight();
        if($widthscale < $heightscale){
            $newwidth = $image->getPixelWidth() * $widthscale;
            $newheight = $image->getPixelHeight() * $widthscale;
            $offsety = (575 - $newheight)/2;
            $newpage->drawImage($image, 10, 10+$offsety, $newwidth+10, $newheight+$offsety+10);
        } else {
            $newwidth = $image->getPixelWidth() * $heightscale;
            $newheight = $image->getPixelHeight() * $heightscale;
            $offsetx = (822 - $newwidth)/2;
            $newpage->drawImage($image, 10+$offsetx, 10, $newwidth+10+$offsetx, $newheight+10);
        }

        $this->_setFontRegular($newpage, 10);
        $newpage->rotate(842, 0, deg2rad(90));
        $newpage->drawText( 'Seddel: ' . $workslipid, 852,820, 'UTF-8' );
        $newpage->rotate(842, 0, deg2rad(-90));
        $this->_getPdf()->pages[] = $newpage;
    }
}