<?php
namespace Magecomp\Emailquotepro\Model\Mail;

class TransportBuilder extends \Magento\Framework\Mail\Template\TransportBuilder
{
    public function addAttachment($pdfString)
    {
        $attachment = new \Zend\Mime\Part($pdfString);
        $attachment->type = \Zend_Mime::TYPE_OCTETSTREAM;
        $attachment->disposition = \Zend_Mime::DISPOSITION_ATTACHMENT;
        $attachment->encoding = \Zend_Mime::ENCODING_BASE64;
        $attachment->filename = "Estimate_Quote.pdf";
        return $attachment;
    }

    public function clearHeader( $headerName )
    {
        if (isset($this->_headers[$headerName])) {
            unset($this->_headers[$headerName]);
        }
        return $this;
    }
}