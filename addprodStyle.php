<Title>Mimi's Pet Shop</Title>
  <link href="/css/bootstrap.min.css" rel="stylesheet" >
  <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #ffb6c1; /* Pastel Pink */
            font-family: 'Arial', sans-serif;
        }

        .container {
            display: inline-block;
            text-align: center;
            padding: 20px;
            color: black;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(255, 231, 167);
            background-color: #C0F9FA;
        }
        p{
            color : orange;
        }

        a {
            color: black; 
            text-decoration: none;
            font-weight: bold;
        }
        h1{
            color: #64E987;
        }
        h2{
            color : black;
            margin-top: -50px;
        }

        .content {
            text-align: center;
            padding: 20px;
            color: black; /* White text for visibility on pink background */
        }
        a:hover {
            color: #ff6b6b; /* Pastel Red */
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        button {
            padding: 5px 10px;
            margin: 2px;
            cursor: pointer;
        }

        button:hover {
            background-color: #4CAF50;
            color: white;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        td.editable {
            cursor: pointer;
        }

        td.editable:hover {
            background-color: #f0f0f0;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        td.editable {
            cursor: pointer;
        }

        td.editable:hover {
            background-color: #f0f0f0;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        td.editable {
            cursor: pointer;
        }

        td.editable:hover {
            background-color: #f0f0f0;
        }
        .modal {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            border: 1px solid #ccc;
            padding: 20px;
            background-color: #fff;
            z-index: 1000;
        }
        form {
    max-width: 500px;
    margin: 2px auto; /* Center the form horizontally and add space at the top */
    background-color: transparent;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0;
    color: black; /* Set text color inside the form to black */
}

form label {
    display: block;
    text-align: left; /* Align labels to the left */
    margin-bottom: 5px; /* Add some space below each label */
}

form input,
form textarea {
    width: 100%;
    padding: 8px;
    margin-bottom: 10px;
    box-sizing: border-box;
}
.search-container {
            margin-bottom: 5px; 
            margin-top: -50px;
        }

        .search-container input[type=text] {
            padding: 8px;
            margin-right: 5px;
        }

        .search-container button {
            padding: 8px 12px;
        }
        .search-container label {
            font-weight: bold;
        }
        .content table td form {
            display: inline-block;
        }

        .content table td form .update,
        .content table td form .delete {
            margin-right: -5px;
        }
        .content table td form .delete{
            margin-right: -100px;
        }
        .search-container {
            display: flex;
            align-items: center;
        }

        .search-container form {
            display: flex;
        }

        .search-container input[type="text"] {
            margin-right: 10px;
        }
        .search-container label {
            margin-right: 10px;
        }
        .search-container button {
            cursor: pointer;
        }
    
    </style>