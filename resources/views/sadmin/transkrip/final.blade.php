<html>

<head>
    <meta charset="utf-8">
</head>

<body>
    <br>
    <table>
        <thead>
            <tr>
                <th>Project ID</th>
                <th>Question Number</th>
                <th>User ID</th>
                <th>Answer</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($questiondetails as $question)
                <tr>
                    <td>{{ $question->nama }}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <br>
</body>

</html>
