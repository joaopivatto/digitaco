export class MatchesController {
    constructor (config) {
        this.config = config
    }

    async getGlobalRating(weekly) {
        const url = weekly ? 
            `${this.config.API_BASE_URL}/matches/global-rating.php`
            : `${this.config.API_BASE_URL}/matches/global-rating-weekly.php`

        const response = await fetch(url, {
            method: "GET",
            headers: { "Content-Type": "application/json" },
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message)
        }

        return data
    }

    async getUserHistory() {
        const response = await fetch(`${this.config.API_BASE_URL}/matches/user-history.php`, {
            method: "GET",
            headers: { "Content-Type": "application/json" },
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message)
        }

        return data
    }

    async create(points, words, leagueId) {
        const payload = {
            points,
            words,
            leagueId
        }

        const response = await fetch(`${this.config.API_BASE_URL}/matches/create.php`, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(payload)
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message)
        }

        return data.message
    }
} 