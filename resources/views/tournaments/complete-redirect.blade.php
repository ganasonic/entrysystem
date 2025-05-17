<form id="completeForm" action="{{ route('entry.complete') }}" method="POST">
    @csrf
    <input type="hidden" name="tournament_id" value="{{ $tournamentId }}">
    <input type="hidden" name="player_count" value="{{ $playerCount }}">
    <input type="hidden" name="sajnoList" value="{{ $sajnoList }}">
    <input type="hidden" name="session_id" value="{{ $sessionId }}">
</form>

<script>
    document.getElementById('completeForm').submit();
</script>
