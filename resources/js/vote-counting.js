document.addEventListener('DOMContentLoaded', () => {
    const positions = document.querySelectorAll('.position-section');

    console.log('Found position sections:', positions.length);

    positions.forEach(position => {
        const positionId = position.dataset.positionId;
        console.log(`Subscribing to channel: position.${positionId}`);

        window.Echo.channel(`position.${positionId}`)
            .listen('.App\\Events\\VoteCast', (event) => {
                console.log('VoteCast event received for position:', positionId, event);

                const candidateRows = position.querySelectorAll('.candidate-details');
                console.log('Found candidate rows:', candidateRows.length);

                const maxVotes = Math.max(...Object.values(event.candidateVotes), 1);
                console.log('Max votes calculated:', maxVotes);

                candidateRows.forEach(row => {
                    const candidateId = row.dataset.candidateId;
                    const voteCount = event.candidateVotes[candidateId] || 0;
                    console.log(`Updating candidate ${candidateId} with vote count: ${voteCount}`);

                    const voteCountElement = row.querySelector('.vote-count');
                    if (voteCountElement) {
                        voteCountElement.textContent = `${voteCount} votes`;
                    } else {
                        console.warn('Vote count element not found for candidate:', candidateId);
                    }

                    const progressBar = row.querySelector('.progress-bar-fill');
                    const progressPercentage = row.querySelector('.progress-percentage');
                    const percentage = ((voteCount / maxVotes) * 100).toFixed(0);
                    console.log(`Calculated percentage for candidate ${candidateId}: ${percentage}%`);

                    if (progressBar && progressPercentage) {
                        progressBar.style.width = `${percentage}%`;
                        progressPercentage.textContent = `${percentage}%`;
                    } else {
                        console.warn('Progress bar or percentage element not found for candidate:', candidateId);
                    }
                });
            })
            .error((error) => {
                console.error('Error subscribing to channel:', error);
            });
    });
});
