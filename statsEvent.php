<?php
/*******************************************************************************
	Event Summary

	Displays information about the event, such as fighter counts for
	each tournament and registrations from each club
	LOGIN
		- ADMIN and above can view the page

*******************************************************************************/

// INITIALIZATION //////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

$pageName = 'Event Summary';
include('includes/header.php');

if($_SESSION['eventID'] == null){
	pageError('event');
} elseif(ALLOW['VIEW_ROSTER'] == false) {
	displayAlert("Event is still upcoming<BR>Roster not yet released");
} else {

	$numParticipants = getNumEventRegistrations($_SESSION['eventID']);
	$numFighters = getNumEventFighters($_SESSION['eventID']);
	$totalTournamentEntries = getNumEventTournamentEntries($_SESSION['eventID']);

	$tournamentList = getTournamentsFull($_SESSION['eventID']);
	$clubTotals = getAttendanceFromSchools($_SESSION['eventID']);

// PAGE DISPLAY ////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
?>

<!-- Participant summary -->

	<div class='grid-x align-center'>
	<div class='large-6 medium-10 small-12'>


	<div class='callout success text-center'>
		<h5>
			Total Event Participants
			<?=tooltip("Organizers will typically <b>NOT</b> enter non-fighting participants into Scorecard, and thus the total attendance may be higher than this number.")?>:
			<strong><?=$numParticipants?></strong><BR>

			Total Event Fighters
			<?=tooltip('Number of participants that fought in a tournament.')?>:
			<strong><?=$numFighters?></strong><BR>

			Total Tournament Registrations
			<?=tooltip('<u>Example</u>: If the same person fights in 3 tournaments they count as 3 registrations.')?>:
			<strong><?=$totalTournamentEntries?></strong>
		</h5>
	</div>

	<table>
	<caption>Participant Numbers</caption>


	<?php foreach((array)$tournamentList as $tID => $data): ?>
		<tr>
			<td><?=getTournamentName($tID )?></td>
			<td class='text-right'>
				<?=$data['numFighters']?>
				<?php if($data['isTeams'] != 0):?>
				(<i><?=$data['numParticipants']?> teams</i>)
			<?php endif ?>
			</td>
		</tr>
	<?php endforeach ?>

		<tr style='border-top:solid 1px'>
			<th class='text-right'>
				<em>Total:</em>
			</th>
			<th class='text-right'>
				<em><?=$totalTournamentEntries?></em>
			</th>
		</tr>
	</table>

<!-- School registrations summary -->

	<table class='data_table'>
			<tr>
				<th>School</th>
				<th>Participants</th>
				<th>Fighters</th>
			</tr>
		<?php foreach((array)$clubTotals as $index => $school):
			if($school['schoolID'] == 1){
				$unknownIndex = $index;
				continue;
			}
			if($school['schoolID'] == 2){
				$unaffiliatedIndex = $index;
				continue;
			}
			$name = getSchoolName($school['schoolID'], 'full', 'Branch');
			?>

			<tr>
				<td><?=$name?></td>
				<td class='text-center'><?=$school['numTotal']?></td>
				<td class='text-center'><?=$school['numFighters']?></td>
			</tr>

		<?php endforeach ?>

		<?php if(isset($unaffiliatedIndex) == true): ?>
			<tr>
				<td><i>Unaffiliated</i></td>
				<td class='text-center'><?=$clubTotals[$unaffiliatedIndex]['numTotal']?></td>
				<td class='text-center'><?=$clubTotals[$unaffiliatedIndex]['numFighters']?></td>
			</tr>
		<?php endif ?>

		<?php if(isset($unknownIndex) == true): ?>
			<tr>
				<td><i>Unknown</i></td>
				<td class='text-center'><?=$clubTotals[$unknownIndex]['numTotal']?></td>
				<td class='text-center'><?=$clubTotals[$unknownIndex]['numFighters']?></td>
			</tr>
		<?php endif ?>

	</table>

	</div>
	</div>

<?php }
include('includes/footer.php');

// FUNCTIONS ///////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

/******************************************************************************/

// END OF DOCUMENT /////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
