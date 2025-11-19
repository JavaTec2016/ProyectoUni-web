<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Adjacent Image Carousel</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* Center the carousel */
        .carousel {
            width: 70%;
            margin: 50px auto;
        }

        /* Adjust the inner container to show partial adjacent slides */
        .carousel-inner {
            display: flex;
            overflow: visible;
        }

        .carousel-item {
            flex: 0 0 33.333%;
            /* 3 items visible (left, active, right) */
            transition: transform 0.6s ease;
            opacity: 0.5;
            transform: scale(0.9);
        }

        .carousel-item.active {
            opacity: 1;
            transform: scale(1);
            z-index: 2;
        }

        .carousel-item img {
            width: 100%;
            border-radius: 15px;
            border: 3px solid #000;
        }

        /* Hide the overflow outside the visible range */
        .carousel-inner {
            overflow: visible;
        }
    </style>
</head>

<body>

    <div id="adjacentCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">

            <div class="carousel-item active">
                <img src="https://picsum.photos/id/1015/600/400" alt="Image 1">
            </div>
            <div class="carousel-item">
                <img src="https://picsum.photos/id/1025/600/400" alt="Image 2">
            </div>
            <div class="carousel-item">
                <img src="https://picsum.photos/id/1035/600/400" alt="Image 3">
            </div>
            <div class="carousel-item">
                <img src="https://picsum.photos/id/1045/600/400" alt="Image 4">
            </div>

        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#adjacentCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#adjacentCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>