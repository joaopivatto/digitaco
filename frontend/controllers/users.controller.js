export class UsersController {
    constructor (config) {
        this.config = config
    }

    async logOut() {
        const response = await fetch(`${this.config.BASE_URL}/users/logout.php`, {
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

    async changePassword(email, password, confirmPassword) {
        const payload = {
            email,
            password,
            confirmPassword
        }

        this.validate(password, "change-password")

        const response = await fetch(`${this.config.BASE_URL}/users/change-password.php`, {
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

        const response = await fetch(`${this.config.BASE_URL}/users/sign-in.php`, {
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

        const response = await fetch(`${this.config.BASE_URL}/users/sign-up.php`, {
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
            const nameIsValid = this.validateName(payload.name)
            if (!nameIsValid) throw new Error("Nome inválido ou vazio.")
        }
        
        if (domain !== "change-password") {
            const emailIsValid = this.validateEmail(payload.email)
            if (!emailIsValid) throw new Error("Email inválido!")
        } else {
            const confirmPasswordIsValid = this.validateConfirmPassword(payload.password, payload.confirmPassword)
            if (!confirmPasswordIsValid) throw new Error("As senhas não coincidem!")
        }
        
        const passwordIsValid = this.validatePassword(payload.password)
        if (!passwordIsValid) throw new Error("Senha inválida!")
        
    }

    validateName(name) {
        if (!name) return false
        return true
    }
    validateEmail(email) {
        const regex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/
        return regex.test(email)
    }
    validatePassword(password) {
        const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/
        return regex.test(password)
    }
    validateConfirmPassword(password, confirmPassword) {
        return password === confirmPassword
    }
}