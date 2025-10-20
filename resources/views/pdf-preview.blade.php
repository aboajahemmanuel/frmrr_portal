<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDF Preview</title>

    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf.worker.min.js"></script>

    <style>
        .pdf-page {
            border: 1px solid #ddd;
            margin-bottom: 10px;
            /* Space between pages */
            width: 100%;
        }
    </style>
</head>

<body>

    <!-- Button to open modal -->
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#pdfModal">
        Preview PDF
    </button>

    <!-- Modal -->
    <div class="modal fade" id="pdfModal" tabindex="-1" role="dialog" aria-labelledby="pdfModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pdfModalLabel">PDF Preview</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="pdf-viewer">
                        <canvas id="page1" class="pdf-page"></canvas>
                        <canvas id="page2" class="pdf-page"></canvas>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        var url = '{{ asset("public/pdf_documents/$filename") }}';

        var pdfjsLib = window['pdfjs-dist/build/pdf'];
        pdfjsLib.GlobalWorkerOptions.workerSrc =
            'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf.worker.min.js'; // Use CDN

        pdfjsLib.getDocument(url).promise.then(function(pdfDoc) {
            // Function to render a page into a canvas
            function renderPage(pageNum, canvasId) {
                pdfDoc.getPage(pageNum).then(function(page) {
                    var viewport = page.getViewport({
                        scale: 2
                    }); // Adjust scale as needed
                    var canvas = document.getElementById(canvasId);
                    var context = canvas.getContext('2d');
                    canvas.height = viewport.height;
                    canvas.width = viewport.width;

                    var renderContext = {
                        canvasContext: context,
                        viewport: viewport
                    };
                    page.render(renderContext);
                });
            }

            // Render first and second pages
            renderPage(1, 'page1');
            renderPage(2, 'page2');
        }).catch(function(error) {
            console.error('Error loading PDF:', error);
        });
    </script>
</body>

</html>
