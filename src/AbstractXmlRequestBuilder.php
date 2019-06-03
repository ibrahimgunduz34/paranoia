<?php
namespace Paranoia;

use DOMDocument;
use DOMNode;

abstract class AbstractXmlRequestBuilder
{
    /** @var DOMDocument */
    private $object;

    /**
     * XmlRequestBuilder constructor.
     * @param string $rootNode
     */
    public function __construct($rootNode)
    {
        $this->object = new DOMDocument("1.0", "utf-8");
        $this->object->formatOutput = true;
        $this->createElement($this->object, $rootNode);
    }

    /**
     * @return DOMDocument
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * @param DOMNode $parent
     * @param string $elementName
     * @param null $value
     * @return \DOMElement
     */
    public function createElement(DOMNode $parent, $elementName, $value = null, $ignoreEmptyValue=false)
    {
        if ($ignoreEmptyValue === true && empty($value)) {
            return null;
        }
        $element = $this->object->createElement($elementName, $value);
        $parent->appendChild($element);
        return $element;
    }

    public function __toString()
    {
        return $this->object->saveXML();
    }
}
