import { Controller } from 'stimulus';
import axios from 'axios';

export default class extends Controller {
    static targets = ['votePostTotal'];
    static values = {
        url: String,
    }

    clickVoteOnPost(event) {
        event.preventDefault();
        const button = event.currentTarget;

        axios.post(this.urlValue, {
             directionOfVotePost: button.value })

            .then((response) => {
                this.votePostTotalTarget.innerHTML = response.data.votes;
            });
    }
}
