<!DOCTYPE html>
<html>
<head>
    <title>Vote Form</title>
</head>
<body>
    <h2>Cast Your Vote</h2>
    <form action="vote.php" method="POST">
        <label for="student_id">Student ID:</label>
        <input type="text" id="student_id" name="student_id" required><br><br>
        
        <label for="voting_code">Voting Code:</label>
        <input type="text" id="voting_code" name="voting_code" required><br><br>
        
        <input type="submit" value="Cast Vote">
    </form>
</body>
</html>