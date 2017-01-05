<div class="ui stackable menu">
  <div class="item">
    <img src="../images/brackets-logo-50.png">
  </div>
	<a class="red item" href="index.php">
		<i class="home icon"></i> Overview
	</a>
    <a class="item" href="problems.php">
        <i class="file icon"></i> Problems
    </a>	
    <a class="item" href="scoreboard.php">
        <i class="file text icon"></i> Scoreboard
    </a>
    <?php if ( have_printing() ) { ?>
        <a class="item" href="print.php">
        	<i class="print icon"></i> Print
        </a>
    <?php } ?>
</div>