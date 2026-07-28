<?php

namespace App\Domain\BulkUpload\Services;

use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportParserService
{
    public const EXPECTED_HEADERS = [
        'name', 'sku', 'barcode', 'category', 'subcategory', 'brand',
        'short_description', 'description', 'price', 'compare_price', 'cost_price',
        'stock', 'weight', 'height', 'width', 'length', 'unit',
        'status', 'tags', 'thumbnail_url', 'gallery_urls',
        'country_of_origin', 'manufacturer_name', 'manufacturer_details',
        'specifications', 'variants',
    ];

    public function parse(string $filePath, string $fileType): array
    {
        if ($fileType === 'csv') {
            return $this->parseCsv($filePath);
        }

        if ($fileType === 'xlsx') {
            return $this->parseXlsx($filePath);
        }

        throw new \InvalidArgumentException("Unsupported file type: {$fileType}");
    }

    public function parseCsv(string $filePath): array
    {
        $handle = fopen($filePath, 'r');
        if ($handle === false) {
            throw new \RuntimeException('Cannot open CSV file for reading');
        }

        $headers = fgetcsv($handle);
        if ($headers === false || $headers === null) {
            fclose($handle);
            throw new \RuntimeException('CSV file is empty or has no headers');
        }

        $headers = array_map('trim', $headers);
        $headers = array_map('strtolower', $headers);

        $rows = [];
        $rowNumber = 1;
        while (($line = fgetcsv($handle)) !== false) {
            $rowNumber++;
            if (count($line) === 1 && ($line[0] === null || trim($line[0]) === '')) {
                continue;
            }

            $row = [];
            foreach ($headers as $index => $header) {
                $row[$header] = $line[$index] ?? '';
            }
            $rows[] = $row;
        }

        fclose($handle);

        return [
            'headers' => $headers,
            'rows' => $rows,
        ];
    }

    public function parseXlsx(string $filePath): array
    {
        if (! class_exists(IOFactory::class)) {
            throw new \RuntimeException('phpoffice/phpspreadsheet is required for XLSX import. Run: composer require phpoffice/phpspreadsheet');
        }

        $spreadsheet = IOFactory::load($filePath);
        $worksheet = $spreadsheet->getActiveSheet();
        $data = $worksheet->toArray(null, true, true, false);

        if (empty($data)) {
            throw new \RuntimeException('XLSX file is empty');
        }

        $headers = array_map('trim', array_map('strtolower', array_shift($data)));

        $rows = [];
        foreach ($data as $rowNumber => $line) {
            if (count(array_filter($line, fn ($v) => $v !== null && $v !== '' && trim((string) $v) !== '')) === 0) {
                continue;
            }

            $row = [];
            foreach ($headers as $index => $header) {
                $row[$header] = $line[$index] ?? '';
            }
            $rows[] = $row;
        }

        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet);

        return [
            'headers' => $headers,
            'rows' => $rows,
        ];
    }

    public function getMissingHeaders(array $headers): array
    {
        $normalizedHeaders = array_map('strtolower', array_map('trim', $headers));

        $required = ['name', 'category', 'price', 'cost_price'];

        return array_diff($required, $normalizedHeaders);
    }

    public function getOptionalHeaders(): array
    {
        return self::EXPECTED_HEADERS;
    }

    public function sanitizeCell(string $value): string
    {
        $value = trim($value);

        if (in_array($value[0] ?? '', ['=', '+', '-', '@'])) {
            return "'" . $value;
        }

        return $value;
    }
}
