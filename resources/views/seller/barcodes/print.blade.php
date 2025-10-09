<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Barcode Labels for {{ $data['productName'] }}</title>
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <style>
        #print-area {
            width: 100%;
        }

        .label-container {
            width: 50mm;
            height: 25mm;
            padding: 1.5mm 3mm;
            border: 1px dashed #aaa;
            box-sizing: border-box;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            margin: 0 auto 10px auto;
        }

        .label-text {
            font-family: "Arial", "Helvetica", sans-serif;
            font-weight: 600;
            text-align: center;
            width: 100%;
            line-height: 1;
        }

        .text-lg {
            font-size: 9pt;
        }

        .text-sm {
            font-size: 7pt;
        }

        .text-xs {
            font-size: 5pt;
        }

        .fw-bold {
            font-weight: 700;
        }

        .fw-normal {
            font-weight: 500;
        }

        .barcode-svg {
            width: 98%;
            height: 50%;
        }

        @media print {
            @page {
                size: 50mm 25mm;
                margin: 0;
            }

            body {
                padding: 0;
                margin: 0;
            }

            .label-container {
                border: none;
                margin: 0;
                padding: 1.5mm 3mm;
                page-break-after: always;
            }

            #print-area {
                margin: 0;
                padding: 0;
            }

            .label-text {
                font-family: "Arial", "Helvetica", sans-serif;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .fw-bold {
                font-weight: 700;
            }

            .fw-normal {
                font-weight: 500;
            }

            .text-lg {
                font-size: 9pt;
            }

            .text-sm {
                font-size: 7pt;
            }
        }
    </style>
</head>

<body>

    <div id="print-area"></div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            printBarcodes();
        });

        function printBarcodes() {
            const sellerName = "{{ $data['sellerName'] }}";
            const productName = "{{ $data['productName'] }}";
            const price = "{{ $data['price'] }}";
            const barcodeData = "{{ $data['sku'] }}";
            const variantName = "{{ $data['variantName'] }}"; // New variant name data
            const labelCount = parseInt("{{ $data['quantity'] }}");
            const printArea = document.getElementById("print-area");

            printArea.innerHTML = "";

            for (let i = 0; i < labelCount; i++) {
                const labelContainer = document.createElement("div");
                labelContainer.className = "label-container";

                const sellerNameElement = document.createElement("div");
                sellerNameElement.className = "label-text fw-bold text-lg";
                sellerNameElement.textContent = sellerName;

                const nameElement = document.createElement("div");
                nameElement.className = "label-text fw-normal text-sm";
                nameElement.textContent = productName;

                const variantElement = document.createElement("div");
                variantElement.className = "label-text fw-normal text-xs";
                if (variantName) {
                    variantElement.textContent = variantName;
                }

                const priceElement = document.createElement("div");
                priceElement.className = "label-text fw-bold text-lg";
                priceElement.textContent = 'Price: ' + price;

                const barcodeElement = document.createElementNS("http://www.w3.org/2000/svg", "svg");
                barcodeElement.className = "barcode-svg";
                barcodeElement.id = `barcode-${i}`;

                labelContainer.appendChild(sellerNameElement);
                labelContainer.appendChild(nameElement);

                if (variantName) {
                    labelContainer.appendChild(variantElement);
                }

                labelContainer.appendChild(barcodeElement);
                labelContainer.appendChild(priceElement);
                printArea.appendChild(labelContainer);

                try {
                    JsBarcode(`#${barcodeElement.id}`, barcodeData, {
                        format: "CODE128",
                        displayValue: true,
                        fontSize: 16,
                        height: 70,
                        margin: 2
                    });
                } catch (e) {
                    console.error(e);
                    alert(
                        "Error generating barcode. Check Barcode Data and try again."
                    );
                    printArea.innerHTML = "";
                    return;
                }
            }
            if (labelCount > 0) {
                window.print();
            }
        }
    </script>
</body>

</html>