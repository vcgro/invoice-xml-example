<?php

declare(strict_types=1);

namespace App\Services\Invoice;

use App\Exceptions\XmlParsingException;
use Dom\Node;
use Dom\XMLDocument;
use Dom\XPath;
use Throwable;

final class InvoiceXmlParser
{
    private ?XMLDocument $document = null;

    /**
     * @throws XmlParsingException
     */
    public function loadData(string $data): self
    {
        try {
            $this->document = XMLDocument::createFromString($data);
            $this->document->formatOutput = true;
        } catch (Throwable) {
            throw new XmlParsingException();
        }

        return $this;
    }

    /**
     * @throws XmlParsingException
     */
    public function toXml(): string
    {
        if ($this->document instanceof XMLDocument) {
            $xml = $this->document->saveXML();

            if (is_string($xml)) {
                return $xml;
            }
        }

        throw new XmlParsingException();
    }

    /**
     * @return array<string, string|null>
     *
     * @throws XmlParsingException
     */
    public function toArray(): array
    {
        if (! $this->document instanceof XMLDocument) {
            throw new XmlParsingException();
        }

        $xpath = new XPath($this->document);

        $xpath->registerNamespace(
            'cbc',
            'urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2',
        );
        $xpath->registerNamespace(
            'cac',
            'urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2',
        );

        return [
            'invoice_number' => $this->getValue(
                $xpath,
                '//cbc:ID',
            ),
            'issue_date' => $this->getValue(
                $xpath,
                '//cbc:IssueDate',
            ),
            'supplier_id' => $this->getValue(
                $xpath,
                '//cac:AccountingSupplierParty//cbc:EndpointID',
                '//cac:AccountingSupplierParty//cac:PartyIdentification//cbc:ID',
            ),
            'customer_id' => $this->getValue(
                $xpath,
                '//cac:AccountingCustomerParty//cbc:EndpointID',
                '//cac:AccountingCustomerParty//cac:PartyIdentification//cbc:ID',
            ),
            'payable_amount' => $this->getValue(
                $xpath,
                '//cbc:PayableAmount',
            ),
        ];
    }

    private function getValue(
        XPath $xpath,
        string $valueName,
        ?string $fallbackValueName = null,
    ): ?string {
        $node = $xpath->query($valueName)->item(0);

        if (! $node instanceof Node && $fallbackValueName) {
            $node = $xpath->query($fallbackValueName)->item(0);
        }

        return $node?->textContent;
    }
}
