<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eitht Tech Consults Limited</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <!-- Custom Styles -->
    <style>
        /* create css var primary color as #056098 */
        :root {
            --primary-color: #056098;
        }

        body {
            font-family: Arial, sans-serif;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background-color: #056098;
            color: #fff;
            text-align: center;
            padding: 10px;
        }

        .content {
            padding: 20px;
            background-color: #fff;
            color: #424649;
        }

        .footer {
            background-color: white;
            text-align: center;
            padding: 10px;
            padding-top: 20px;
        }

        .text-primary {
            color: var(--primary-color) !important;
        }
    </style>
</head>

<body style="background-color: #e7f6ff; background: #e7f6ff;">

    <div class="email-container" style="background-color: #e7f6ff; background: #e7f6ff;">
        <!-- Header -->
        <div class="footer" style="border-bottom: 2px solid #056098;">
            <h2 style="color: #056098;">{{ env('APP_NAME') }}</h2>
        </div>
        <div class=""
            style="padding-top: 10px; padding-bottom: 10px; border-bottom: 2px solid #056098;
        text-align: center;
        ">
            <a class="text-dark small " style="color: #424649"
                href="https://app.ict4personswithdisabilities.org/">Observatory</a> •
            <a class="text-dark small " style="color: #424649" href="https://app.ict4personswithdisabilities.org/">Our
                Services</a> •
            <a class="text-dark small " style="color: #424649"
                href="https://app.ict4personswithdisabilities.org/about-us">About
                Us</a> •
        </div>
        <!-- Content -->
        <div class="content"
            style="
        font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
        font-size: 16px;
        ">
            {!! $body !!}</div>
        <div class="row">
            <div class="col-md-6">
                <a href="https://nudipu.org/">
                    <div class="header small">
                        <h4>National Union of Disabled Persons of Uganda (NUDIPU)</h4>
                        {{-- <p class="small">DISABILITY • INNOVATION • TECHNOLOGY • SERVICES</p> --}}
                    </div>
                </a>
            </div>
            <div class="col-md-6">
                <a href="https://8technologies.net">
                    <div class="header small" style="background: rgb(135, 243, 135)">
                        <h4 style="color:#056098">Eight Tech Consults</h4>
                        {{-- <p class="small">PEOPLE • INNOVATION • TECHNOLOGY • SERVICES</p> --}}
                    </div>
                </a>
            </div>
        </div>

    </div>
</body>

</html>
