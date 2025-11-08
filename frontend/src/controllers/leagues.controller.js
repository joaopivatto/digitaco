import { validateName, validatePassword } from "../utils/validations.js"

export class LeaguesController {
    constructor (config) {
        this.config = config
    }

    async getLeaguePointsWeekly(leagueId) {
        const response = await fetch(`${this.config.API_BASE_URL}/leagues/points-weekly.php?id=${leagueId}`, {
            method: "GET",
            headers: { "Content-Type": "application/json" },
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message)
        }

        return data
    }

    async getLeaguePoints(leagueId) {
        const response = await fetch(`${this.config.API_BASE_URL}/leagues/points.php?id=${leagueId}`, {
            method: "GET",
            headers: { "Content-Type": "application/json" },
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message)
        }

        return data
    }

    async deleteLeague(leagueId) {
        const response = await fetch(`${this.config.API_BASE_URL}/leagues/delete.php?id=${leagueId}`, {
            method: "GET",
            headers: { "Content-Type": "application/json" },
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message)
        }

        return data
    }

    async getLeaguesUserIsIncluded() {
        const response = await fetch(`${this.config.API_BASE_URL}/leagues/creator.php`, {
            method: "GET",
            headers: { "Content-Type": "application/json" },
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message)
        }

        return data
    }

    async getLeaguesUserIsCreator() {
        const response = await fetch(`${this.config.API_BASE_URL}/leagues/creator.php`, {
            method: "GET",
            headers: { "Content-Type": "application/json" },
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message)
        }

        return data
    }


    async findById(leagueId) {
        const response = await fetch(`${this.config.API_BASE_URL}/leagues/find-by-id.php?id=${leagueId}`, {
            method: "GET",
            headers: { "Content-Type": "application/json" },
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message)
        }

        return data
    }

    async findAll(name) {
        const url = name 
        ? `${this.config.API_BASE_URL}/leagues/find-all.php?name=${encodeURIComponent(name)}`
        : `${this.config.API_BASE_URL}/leagues/find-all.php`;
        
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

    async create(name, password) {
        const passwordIsValid = validatePassword(password)
        if (!passwordIsValid) throw new Error("Senha inválida!")
        
        const nameIsValid = validateName(name)
        if (!nameIsValid) throw new Error("Nome inválido!")
        
        const payload = {
            name,
            password,
        }

        const response = await fetch(`${this.config.API_BASE_URL}/leagues/create.php`, {
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