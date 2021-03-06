import { Controller } from 'stimulus';
import axios from 'axios';

export default class extends Controller {
    static targets = ['voteCommentTotal'];
    static values = {
        url: String,
    }

    clickVoteOnComment(event) {
        event.preventDefault();
        const button = event.currentTarget;

        axios.post(this.urlValue, {
           directionOfVoteComment: button.value })

            .then((response) => {
                this.voteCommentTotalTarget.innerHTML = response.data.votes;
            })
        ;
    }
}
