<?php echo file_get_contents("header.html"); ?>
    
    <h2>Meet Our Pups!</h2>
    
    <?php
	$puppyname = "Charles";
	$imgsrc = "img/pup1.png";
	$puppybio = "Charles has been providing joy and comfort to Rent-A-Pup customers since October, when his mom moved out to the country and had to find a good home for Charles. Little Charlie is a Husky puppy, and loves wrestling, making friends, and puppy yoga. Charlie is also a big hit with the ladies and is never shy meeting new people.";
	
	$table = file_get_contents("rimg.html");
	$table = str_replace("!!!TITLE!!!", $puppyname, $table);
	$table = str_replace("!!!IMGSRC!!!", $imgsrc, $table);
	$table = str_replace("!!!CAPTION!!!", $puppybio, $table);
	
	echo $table;
	?>
    
    <?php
	$puppyname = "Chunk";
	$imgsrc = "img/pup2.png";
	$puppybio = "Chunk has been with the Rent-A-Pup family since December, and is an energetic bundle of fur. Chunk loves racing, belly rubs and playing with kids. He's always hungry, and loves to sneak food when you aren't paying attention. His favorite foods are marshmallows and peanut butter.";
	
	$table = file_get_contents("limg.html");
	$table = str_replace("!!!TITLE!!!", $puppyname, $table);
	$table = str_replace("!!!IMGSRC!!!", $imgsrc, $table);
	$table = str_replace("!!!CAPTION!!!", $puppybio, $table);
	
	echo $table;
	?>
    
    <?php
	$puppyname = "Goose";
	$imgsrc = "img/pup3.png";
	$puppybio = "Goose is our newest pup! He's an energetic Jack Russell terrier who's not afraid to make a little noise. Goose was named after his favorite animal, who he loves to chase in the park. Goose is great with kids, and loves to play fetch and do spins and other cool tricks for an audience.";
	
	$table = file_get_contents("rimg.html");
	$table = str_replace("!!!TITLE!!!", $puppyname, $table);
	$table = str_replace("!!!IMGSRC!!!", $imgsrc, $table);
	$table = str_replace("!!!CAPTION!!!", $puppybio, $table);
	
	echo $table;
	?>
    
    <?php
	$puppyname = "Sheila";
	$imgsrc = "img/pup4.png";
	$puppybio = "Sheila has been part of the family since December. She's the youngest of our pups, but you'd never know it since she spends her days bossing her brothers around. Sheila loves people-watching from windows, and is great for making friends. She loves taking car rides, and her favorite food is breakfast sausage. Much fun. Wow.";
	
	$table = file_get_contents("limg.html");
	$table = str_replace("!!!TITLE!!!", $puppyname, $table);
	$table = str_replace("!!!IMGSRC!!!", $imgsrc, $table);
	$table = str_replace("!!!CAPTION!!!", $puppybio, $table);
	
	echo $table;
	?>
      
<?php echo file_get_contents("footer.html"); ?>