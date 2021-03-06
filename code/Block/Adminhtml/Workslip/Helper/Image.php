<?php
class Digidennis_WorkSlip_Block_Adminhtml_Workslip_Helper_Image extends Varien_Data_Form_Element_Image{
    //make your renderer allow "multiple" attribute
    public function getHtmlAttributes(){
        return array_merge(parent::getHtmlAttributes(), array('multiple'));
    }

    public function getElementHtml()
    {
        $html = "<div style='width: 100%;' id=\"fine-uploader\"></div>
        <script type=\"text/template\" id=\"qq-template\">
            <div class=\"qq-uploader-selector qq-uploader\" qq-drop-area-text=\"Træk og Slip\">
                <div class=\"qq-total-progress-bar-container-selector qq-total-progress-bar-container\">
                    <div role=\"progressbar\" aria-valuenow=\"0\" aria-valuemin=\"0\" aria-valuemax=\"100\" class=\"qq-total-progress-bar-selector qq-progress-bar qq-total-progress-bar\"></div>
                </div>
                <div class=\"qq-upload-drop-area-selector qq-upload-drop-area\" qq-hide-dropzone>
                    <span class=\"qq-upload-drop-area-text-selector\"></span>
                </div>
                <div class=\"qq-upload-button-selector qq-upload-button\">
                    <div>Upload fil</div>
                </div>
                <span class=\"qq-drop-processing-selector qq-drop-processing\">
                    <span>Beregner...</span>
                    <span class=\"qq-drop-processing-spinner-selector qq-drop-processing-spinner\"></span>
                </span>
                <ul class=\"qq-upload-list-selector qq-upload-list\" aria-live=\"polite\" aria-relevant=\"additions removals\">
                    <li>
                        <div class=\"qq-progress-bar-container-selector\">
                            <div role=\"progressbar\" aria-valuenow=\"0\" aria-valuemin=\"0\" aria-valuemax=\"100\" class=\"qq-progress-bar-selector qq-progress-bar\"></div>
                        </div>
                        <span class=\"qq-upload-spinner-selector qq-upload-spinner\"></span>
                        <img class=\"qq-thumbnail-selector\" qq-max-size=\"100\" qq-server-scale>
                        <span class=\"qq-upload-file-selector qq-upload-file\"></span>
                        <span class=\"qq-edit-filename-icon-selector qq-edit-filename-icon\" aria-label=\"Edit filename\"></span>
                        <input class=\"qq-edit-filename-selector qq-edit-filename\" tabindex=\"0\" type=\"text\">
                        <span class=\"qq-upload-size-selector qq-upload-size\"></span>
                        <button type=\"button\" class=\"qq-btn qq-upload-cancel-selector qq-upload-cancel\">Annuller</button>
                        <button type=\"button\" class=\"qq-btn qq-upload-retry-selector qq-upload-retry\">Prøv igen</button>
                        <button type=\"button\" class=\"qq-btn qq-upload-delete-selector qq-upload-delete\">Slet</button>
                        <span role=\"status\" class=\"qq-upload-status-text-selector qq-upload-status-text\"></span>
                    </li>
                </ul>

                <dialog class=\"qq-alert-dialog-selector\">
                    <div class=\"qq-dialog-message-selector\"></div>
                    <div class=\"qq-dialog-buttons\">
                        <button type=\"button\" class=\"qq-cancel-button-selector\">Luk</button>
                    </div>
                </dialog>

                <dialog class=\"qq-confirm-dialog-selector\">
                    <div class=\"qq-dialog-message-selector\"></div>
                    <div class=\"qq-dialog-buttons\">
                        <button type=\"button\" class=\"qq-cancel-button-selector\">No</button>
                        <button type=\"button\" class=\"qq-ok-button-selector\">Yes</button>
                    </div>
                </dialog>

                <dialog class=\"qq-prompt-dialog-selector\">
                    <div class=\"qq-dialog-message-selector\"></div>
                    <input type=\"text\">
                    <div class=\"qq-dialog-buttons\">
                        <button type=\"button\" class=\"qq-cancel-button-selector\">Cancel</button>
                        <button type=\"button\" class=\"qq-ok-button-selector\">Ok</button>
                    </div>
                </dialog>
            </div>
        </script>
        <script>
            var uploader = new qq.FineUploader({
                element: document.getElementById('fine-uploader'),
                validation:{
                    allowedExtensions: ['jpg', 'png', 'jpeg'],
                    acceptFiles: 'application/pdf,image/jpeg,image/png',
                    itemLimit: 10,
                    sizeLimit: 4000000
                },
                session: {
                    endpoint: '" .Mage::helper("adminhtml")->getUrl('*/*/imageinit') . "',
                },
                request: {
                    endpoint: '" .Mage::getUrl('*/*/imageupload') . "',
                    params: {
                        form_key: \"" . Mage::getSingleton('core/session')->getFormKey() . "\"
                    }
                },
                deleteFile: {
                    enabled: true,
                    endpoint: '" .Mage::getUrl('*/*/imagedelete') . "',
                    method: 'POST',
                    params: {
                        form_key: \"" . Mage::getSingleton('core/session')->getFormKey() . "\"
                    }
                }
            });
        </script>";
        return $html;
    }

}