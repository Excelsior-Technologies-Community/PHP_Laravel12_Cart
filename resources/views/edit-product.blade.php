<!DOCTYPE html>
<html>

<head>

    <title>Edit Product</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>

        body{
            background: #f5f7fb;
        }

        .form-card{
            background: white;
            padding: 35px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        }

        .form-title{
            font-weight: 600;
        }

        .image-preview{
            width: 120px;
            height: 120px;
            border: 1px dashed #ccc;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background: #fafafa;
        }

        .image-preview img{
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

    </style>

</head>

<body>

<div class="container mt-5">

    <div class="row justify-content-center">

        <div class="col-md-7">

            <div class="form-card">

                <div class="d-flex justify-content-between align-items-center mb-4">

                    <h3 class="form-title">

                        Edit Product

                    </h3>

                    <a href="/"
                       class="btn btn-outline-primary">

                        Back

                    </a>

                </div>

                <!-- SUCCESS MESSAGE -->
                @if(session('success'))

                    <div class="alert alert-success">

                        {{ session('success') }}

                    </div>

                @endif

                <!-- VALIDATION ERRORS -->
                @if($errors->any())

                    <div class="alert alert-danger">

                        <ul class="mb-0">

                            @foreach($errors->all() as $error)

                                <li>{{ $error }}</li>

                            @endforeach

                        </ul>

                    </div>

                @endif

                <form action="/update-product/{{ $product->id }}"
                      method="POST"
                      enctype="multipart/form-data">

                    @csrf

                    <!-- PRODUCT NAME -->
                    <div class="mb-3">

                        <label class="form-label">

                            Product Name

                        </label>

                        <input type="text"
                               name="name"
                               class="form-control"
                               value="{{ $product->name }}"
                               placeholder="Enter product name">

                    </div>

                    <!-- DESCRIPTION -->
                    <div class="mb-3">

                        <label class="form-label">

                            Description

                        </label>

                        <textarea name="description"
                                  rows="3"
                                  class="form-control"
                                  placeholder="Enter product description">{{ $product->description }}</textarea>

                    </div>

                    <!-- PRICE -->
                    <div class="mb-3">

                        <label class="form-label">

                            Price

                        </label>

                        <input type="number"
                               name="price"
                               class="form-control"
                               value="{{ $product->price }}"
                               placeholder="Enter product price">

                    </div>

                    <!-- IMAGE -->
                    <div class="mb-4">

                        <label class="form-label">

                            Product Image

                        </label>

                        <input type="file"
                               name="image"
                               class="form-control"
                               onchange="previewImage(event)">

                        <div class="mt-3 image-preview"
                             id="previewBox">

                            @if($product->image)

                                <img src="{{ asset('storage/'.$product->image) }}">

                            @else

                                <span class="text-muted small">

                                    Preview

                                </span>

                            @endif

                        </div>

                    </div>

                    <!-- BUTTON -->
                    <button class="btn btn-success w-100">

                        Update Product

                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

<script>

    function previewImage(event){

        const previewBox = document.getElementById('previewBox');

        previewBox.innerHTML = '';

        const img = document.createElement('img');

        img.src = URL.createObjectURL(event.target.files[0]);

        previewBox.appendChild(img);

    }

</script>

</body>

</html>