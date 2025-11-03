export class UsersController {
    constructor (config) {
        this.config = config
    }

    async signUp(name, email, password) {
        const payload = {
            name,
            email,
            password,
        }

        this.validate(payload)

        const response = await fetch(`${this.config.BASE_URL}/users/sign-up`, {
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
    
    validate(payload) {
        const nameIsValid = this.validateName(payload.name)
        if (!nameIsValid) throw new Error("Nome inválido ou vazio.")
        
        const emailIsValid = this.validateEmail(payload.email)
        if (!emailIsValid) throw new Error("Email inválido!")

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
}