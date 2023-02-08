<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verfied - Pishewer</title>
    <style>
        .check {
            fill: none;
            stroke: black;
            stroke-linecap: round;
            stroke-linejoin: round;
            stroke-miterlimit: 10;
        }

        .check {
            stroke-dasharray: 60 200;
            animation: check 2.75s cubic-bezier(0.5, 0, 0.6, 1) forwards 0.0s;
            opacity: 0;
        }

        @-webkit-keyframes check {
            from {
                stroke-dashoffset: 60;
                opacity: 1;
            }

            to {
                stroke-dashoffset: 293;
                opacity: 1;
            }
        }





        .myButton {
            -moz-box-shadow: inset 0px 1px 0px 0px #ffffff;
            -webkit-box-shadow: inset 0px 1px 0px 0px #ffffff;
            box-shadow: inset 0px 1px 0px 0px #ffffff;
            background: -webkit-gradient(linear, left top, left bottom, color-stop(0.05, #ffffff), color-stop(1, #f6f6f6));
            background: -moz-linear-gradient(top, #ffffff 5%, #f6f6f6 100%);
            background: -webkit-linear-gradient(top, #ffffff 5%, #f6f6f6 100%);
            background: -o-linear-gradient(top, #ffffff 5%, #f6f6f6 100%);
            background: -ms-linear-gradient(top, #ffffff 5%, #f6f6f6 100%);
            background: linear-gradient(to bottom, #ffffff 5%, #f6f6f6 100%);
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffffff', endColorstr='#f6f6f6', GradientType=0);
            background-color: #ffffff;
            -moz-border-radius: 3px;
            -webkit-border-radius: 3px;
            border-radius: 3px;
            border: 1px solid #dcdcdc;
            display: inline-block;
            cursor: pointer;
            color: #1E4164;
            font-family: Arial;
            font-size: 15px;
            font-weight: regular;
            padding: 6px 24px;
            text-decoration: none;
            text-shadow: 0px 2px 0px #ffffff;
        }

        .myButton:hover {
            background: -webkit-gradient(linear, left top, left bottom, color-stop(0.05, #f6f6f6), color-stop(1, #ffffff));
            background: -moz-linear-gradient(top, #f6f6f6 5%, #ffffff 100%);
            background: -webkit-linear-gradient(top, #f6f6f6 5%, #ffffff 100%);
            background: -o-linear-gradient(top, #f6f6f6 5%, #ffffff 100%);
            background: -ms-linear-gradient(top, #f6f6f6 5%, #ffffff 100%);
            background: linear-gradient(to bottom, #f6f6f6 5%, #ffffff 100%);
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#f6f6f6', endColorstr='#ffffff', GradientType=0);
            background-color: #f6f6f6;
        }

        .myButton:active {
            position: relative;
            top: 1px;
        }

        .smaller {
            width: 50%;
            margin-left: auto;
            margin-right: auto;
        }
    </style>
</head>

<body>
    <div class="smaller"><svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 60 60" style="enable-background:new 0 0 60 60;" xml:space="preserve">
            <style type="text/css">
            </style>
            <path class="check" d="M40.61,23.03L26.67,36.97L13.495,23.788c-1.146-1.147-1.359-2.936-0.504-4.314
              c3.894-6.28,11.169-10.243,19.283-9.348c9.258,1.021,16.694,8.542,17.622,17.81c1.232,12.295-8.683,22.607-20.849,22.042
              c-9.9-0.46-18.128-8.344-18.972-18.218c-0.292-3.416,0.276-6.673,1.51-9.578" />
        </svg>
    </div>
    <div style="text-align: center; margin: auto; padding: 25px;">

        <a href="#" class="myButton" type=button value="Refresh" onClick="history.go()">Refresh</a>
    </div>
</body>

</html>