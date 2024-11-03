<?php
require 'db_connect.php';
require 'lotto.php' ;

$userNumbers = $_POST['userNumbers'];

if (count($userNumbers) !== count(array_unique($userNumbers))) {
    echo json_encode(['error' => 'Liczby muszą być unikalne!']);
    exit();
}

$randomNumbers = [];
while (count($randomNumbers) < 6) {
    $number = rand(1, 49);
    if (!in_array($number, $randomNumbers)) {
        $randomNumbers[] = $number;
    }
}

$matches = array_intersect($userNumbers, $randomNumbers);
$numberOfMatches = count($matches);
$prize = calculatePrize($numberOfMatches);

$userNumbersStr = implode(", ", $userNumbers);
$randomNumbersStr = implode(", ", $randomNumbers);
$sql = "INSERT INTO results (user_numbers, random_numbers, matches) VALUES (:user_numbers, :random_numbers, :matches)";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':user_numbers', $userNumbersStr);
$stmt->bindParam(':random_numbers', $randomNumbersStr);
$stmt->bindParam(':matches', $numberOfMatches);
$stmt->execute();

$response = [
    'userNumbers' => $userNumbers,
    'randomNumbers' => $randomNumbers,
    'matches' => $matches,
    'numberOfMatches' => $numberOfMatches,
    'prize' => $prize
];
header('Content-Type: application/json');
echo json_encode($response);


function calculatePrize($numberOfMatches) {
    switch ($numberOfMatches) {
        case 6:
            return "1 000 000 zł";
        case 5:
            return "50 000 zł";
        case 4:
            return "5 000 zł";
        case 3:
            return "500 zł";
        default:
            return "0 zł";
    }
}
?>
