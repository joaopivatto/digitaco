import { Config } from '../config/index.js';
import { League } from '../entities/league.js';
import { Rating } from '../entities/rating.js';
import { validateName, validatePassword } from '../utils/validations.js';

export class LeaguesController {
  private config: Config;

  constructor(config: Config) {
    this.config = config;
  }

  async getLeaguePointsWeekly(leagueId: string): Promise<Rating[]> {
    const response = await fetch(
      `${this.config.API_BASE_URL}/leagues/points-weekly.php?id=${leagueId}`,
      {
        method: 'GET',
        headers: { 'Content-Type': 'application/json' },
      }
    );

    const data = await response.json();

    if (!response.ok) {
      throw new Error(data.message);
    }

    return data.data as Rating[];
  }

  async getLeaguePoints(leagueId: string): Promise<Rating[]> {
    const response = await fetch(`${this.config.API_BASE_URL}/leagues/points.php?id=${leagueId}`, {
      method: 'GET',
      headers: { 'Content-Type': 'application/json' },
    });

    const data = await response.json();

    if (!response.ok) {
      throw new Error(data.message);
    }

    return data.data as Rating[];
  }

  async deleteLeague(leagueId: number): Promise<string> {
    const response = await fetch(`${this.config.API_BASE_URL}/leagues/delete.php?id=${leagueId}`, {
      method: 'GET',
      headers: { 'Content-Type': 'application/json' },
    });

    const data = await response.json();

    if (!response.ok) {
      throw new Error(data.message);
    }

    return data.message;
  }

  async getLeaguesUserIsIncluded(): Promise<League[]> {
    const response = await fetch(`${this.config.API_BASE_URL}/leagues/creator.php`, {
      method: 'GET',
      headers: { 'Content-Type': 'application/json' },
    });

    const data = await response.json();

    if (!response.ok) {
      throw new Error(data.message);
    }

    return data.data as League[];
  }

  async getLeaguesUserIsCreator(): Promise<League[]> {
    const response = await fetch(`${this.config.API_BASE_URL}/leagues/creator.php`, {
      method: 'GET',
      headers: { 'Content-Type': 'application/json' },
    });

    const data = await response.json();

    if (!response.ok) {
      throw new Error(data.message);
    }

    return data.data as League[];
  }

  async findById(leagueId: number): Promise<League> {
    const response = await fetch(
      `${this.config.API_BASE_URL}/leagues/find-by-id.php?id=${leagueId}`,
      {
        method: 'GET',
        headers: { 'Content-Type': 'application/json' },
      }
    );

    const data = await response.json();

    if (!response.ok) {
      throw new Error(data.message);
    }

    return data.league as League;
  }

  async findAll(name?: string): Promise<League[]> {
    const url = name
      ? `${this.config.API_BASE_URL}/leagues/find-all.php?name=${encodeURIComponent(name)}`
      : `${this.config.API_BASE_URL}/leagues/find-all.php`;

    const response = await fetch(url, {
      method: 'GET',
      headers: { 'Content-Type': 'application/json' },
    });

    const data = await response.json();

    if (!response.ok) {
      throw new Error(data.message);
    }

    return data.data as League[];
  }

  async create(input: { name: string; password: string }) {
    const passwordIsValid = validatePassword(input.password);
    if (!passwordIsValid) throw new Error('Senha inválida!');

    const nameIsValid = validateName(input.name);
    if (!nameIsValid) throw new Error('Nome inválido!');

    const response = await fetch(`${this.config.API_BASE_URL}/leagues/create.php`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(input),
    });

    const data = await response.json();

    if (!response.ok) {
      throw new Error(data.message);
    }

    return data.message;
  }
}
