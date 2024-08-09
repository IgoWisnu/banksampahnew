<!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8" />
            <meta http-equiv="X-UA-Compatible" content="IE=edge" />
            <meta name="viewport" content="width=device-width, initial-scale=1.0" />
            <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
            <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
            <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" />
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css">
            <link rel="shortcut icon" type="image/x-icon" href="<?=base_url()?>img/logo white.png" />
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"></script>
            <title>Admin Dashboard</title>
        </head>

        <style>
            :root {
            --main-bg-color: #009d63;   
            --main-text-color: #009d63;
            --second-text-color: #bbbec5;
            --second-bg-color: #c1efde;
            }

            .containered {
                background-color: rgba(255, 255, 255, 0.9);
                width: 100%;
                border-radius: 10px; 
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                margin-top: 20px;
            }

            .primary-text {
            color: var(--main-text-color);
            }

            .second-text {
            color: var(--second-text-color);
            }

            #empty-container {
                background-color: blue;
                padding: 20px;
                margin-top: 20px;
                color: white;
            }

            .primary-bg {
            background-color: var(--main-bg-color);
            }

            .secondary-bg {
            background-color: var(--second-bg-color);
            }

            .rounded-full {
            border-radius: 100%;
            }

            #wrapper {
            overflow-x: hidden;
            background-color: rgb(0,146,110);
            }
        

            #sidebar-wrapper {
            min-height: 100vh;
            margin-left: -15rem;
            -webkit-transition: margin 0.25s ease-out;
            -moz-transition: margin 0.25s ease-out;
            -o-transition: margin 0.25s ease-out;
            transition: margin 0.25s ease-out;
            }

            #sidebar-wrapper .sidebar-heading {
            padding: 0.875rem 1.25rem;
            font-size: 1.2rem;
            }

            #sidebar-wrapper .list-group {
            width: 15rem;
            
            }

            #page-content-wrapper {
            min-width: 100vw;
            } 

            #wrapper.toggled #sidebar-wrapper {
            margin-left: 0;
            }

            #menu-toggle {
            cursor: pointer;
            color: black;
            }

            .list-group-item {
            border: none;
            padding: 20px 30px;
            }

            .list-group-item.active {
            background-color: transparent;
            color: var(--main-text-color);
            font-weight: bold;
            border: none;
            }

            @media (min-width: 1100px) {
                #sidebar-wrapper {
                    margin-left: 0;
                }

                #page-content-wrapper {
                    min-width: 0;
                    width: 100%;
                }

                #wrapper.toggled #sidebar-wrapper {
                    margin-left: -15rem;
                    z-index: 1000;
                }

                .popup-container {
                    display: none;
                    position: fixed;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                    background-color: white;
                    padding: 20px;
                    border-radius: 8px;
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                    z-index: 1000;
                }

                .ql-editor {
                    background-color: white !important;
                    border: 1px solid #ccc; /* Optional: Add a border for better visibility */
                    min-height: 150px; /* Adjust the height as needed */
                }

                /* Add this style to set the background of the Quill toolbar to white */
                .ql-toolbar {
                    background-color: white !important;
                }

                /* Additional styling for better appearance */
                #editor {
                    border: 1px solid #ccc;
                    border-radius: 10px;
                    margin-top: 8px;
                    margin-bottom: 16px;
                }
            }

        </style>

<body>