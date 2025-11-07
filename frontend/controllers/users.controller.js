import { 
    validateConfirmPassword,
    validateEmail,
    validateName,
    validatePassword } from "../utils/validations.js";

export class UsersController {
    constructor (config) {
        this.config = config
    }

    async joinLeague(leagueId) {
        const response = await fetch(`${this.config.API_BASE_URL}/users/league/${leagueId}`, {
            method: "POST",
            headers: { "Content-Type": "application/json" }
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message)
        }

        return data.message
    }

    async leaveLeague(leagueId) {
        const response = await fetch(`${this.config.API_BASE_URL}/users/league/${leagueId}`, {
            method: "DELETE",
            headers: { "Content-Type": "application/json" }
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message)
        }

        return data.message
    }

    async logOut() {
        const response = await fetch(`${this.config.API_BASE_URL}/users/logout.php`, {
            method: "POST",
            headers: { "Content-Type": "application/json" }
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message)
        }

        return true
    }

    async changePassword(email, password, confirmPassword) {
        const payload = {
            email,
            password,
            confirmPassword
        }

        this.validate(password, "change-password")

        const response = await fetch(`${this.config.API_BASE_URL}/users/change-password.php`, {
            method: "PATCH",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(payload)
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message)
        }

        return true
    }

    async signIn(email, password) {
        const payload = {
            email,
            password
        }

        this.validate(payload, "sign-in")

        const response = await fetch(`${this.config.API_BASE_URL}/users/sign-in.php`, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(payload)
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message)
        }

        return true
    }

    async signUp(name, email, password) {
        const payload = {
            name,
            email,
            password,
        }

        this.validate(payload, "sign-up")

        const response = await fetch(`${this.config.API_BASE_URL}/users/sign-up.php`, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(payload)
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message)
        }

        return true
    }
    
    validate(payload, domain) {
        if (domain === "sign-up") {
            const nameIsValid = validateName(payload.name)
            if (!nameIsValid) throw new Error("Nome inválido ou vazio.")
        }
        
        if (domain !== "change-password") {
            const emailIsValid = validateEmail(payload.email)
            if (!emailIsValid) throw new Error("Email inválido!")
        } else {
            const confirmPasswordIsValid = validateConfirmPassword(payload.password, payload.confirmPassword)
            if (!confirmPasswordIsValid) throw new Error("As senhas não coincidem!")
        }
        
        const passwordIsValid = validatePassword(payload.password)
        if (!passwordIsValid) throw new Error("Senha inválida!")
        
    }
}