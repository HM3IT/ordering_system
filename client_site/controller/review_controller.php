<?php

require "../../dao/connection.php";
if (session_status() == PHP_SESSION_NONE) {
	session_start();
}

if (isset($_POST["customer_review"])) {


	$data = array(
		':product_id' =>	$_POST["product_id"],
		':customer_id'		=>	$_POST["customer_id"],
		':rating'		=>	$_POST["star_rating"],
		':customer_review'		=>	$_POST["customer_review"],
		':datetime'			=>	time()
	);

	$query = "
	INSERT INTO product_review
	(product_id, customer_id, rating, comments, created_date) 
	VALUES (:product_id, :customer_id, :rating, :customer_review, :datetime)
	";

	$statement = $connection->prepare($query);

	$statement->execute($data);

	echo "Your Review & Rating Successfully Submitted";
}


// load data (review)
if (isset($_POST["action"])) {
//For debugging
// $product_id = $_GET["product_id"];
$product_id = $_POST["product_id"];
$average_rating = 0;
$total_review = 0;

$five_star_review = 0;
$four_star_review = 0;
$three_star_review = 0;
$two_star_review = 0;
$one_star_review = 0;

$total_user_rating = 0;

$review_content = array();

$query = "
    SELECT *
    FROM product_review
    JOIN customer ON product_review.customer_id = customer.id
    WHERE product_review.product_id = $product_id
    ORDER BY product_review.id DESC
";

$result = $connection->query($query, PDO::FETCH_ASSOC);

foreach ($result as $row) {
	$review_content[] = array(
		'product_id'  => $row["product_id"],
		'customer_id'  => $row["customer_id"],
		'image' => $row["image"],
		'customer_name'		=>	$row["name"],
		'customer_review'	=>	$row["comments"],
		'rating'		=>	$row["rating"],
		'datetime'		=>	date('l jS, F Y h:i:s A', $row["created_date"])
	);


	switch ($row["rating"]) {
		case '5':
			$five_star_review++;
			break;
		case '4':
			$four_star_review++;
			break;
		case '3':
			$three_star_review++;
			break;
		case '2':
			$two_star_review++;
			break;
		case '1':
			$one_star_review++;
			break;
	}
	$total_review++;

	$total_user_rating = $total_user_rating + $row["rating"];
}

if ($total_review > 0) {
	$average_rating = $total_user_rating / $total_review;
}

$output = array(
	'average_rating'	=>	number_format($average_rating, 1),
	'total_review'		=>	$total_review,
	'five_star_review'	=>	$five_star_review,
	'four_star_review'	=>	$four_star_review,
	'three_star_review'	=>	$three_star_review,
	'two_star_review'	=>	$two_star_review,
	'one_star_review'	=>	$one_star_review,
	'review_data'		=>	$review_content
);

echo json_encode($output);
}
