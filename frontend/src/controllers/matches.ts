import { Config } from '../config';
import { League } from '../entities/league';
import { Match } from '../entities/match';
import { Rating } from '../entities/rating';
import { User } from '../entities/user';

export interface UserHistoryOutput {
  message: string;
  userPerformance: {
    totalMatches: number;
    totalWords: number;
    totalPoints: number;
    bestScore: number;
    matches: Match[];
  };
}

export class MatchesController {
  private config: Config;

  constructor(config: Config) {
    this.config = config;
  }

  async getGlobalRating(weekly: boolean): Promise<Rating[]> {
    const url = weekly
      ? `${this.config.API_BASE_URL}/matches/global-rating.php`
      : `${this.config.API_BASE_URL}/matches/global-rating-weekly.php`;

    const response = await fetch(url, {
      method: 'GET',
      headers: { 'Content-Type': 'application/json' },
    });

    const data = await response.json();

    if (!response.ok) {
      throw new Error(data.message);
    }

    return data.data as Rating[];
  }

  async getUserHistory(): Promise<UserHistoryOutput> {
    const response = await fetch(`${this.config.API_BASE_URL}/matches/user-history.php`, {
      method: 'GET',
      headers: { 'Content-Type': 'application/json' },
    });

    const data = await response.json();

    if (!response.ok) {
      throw new Error(data.message);
    }

    return data as UserHistoryOutput;
  }

  async create(input: Match & { leagueId: number }): Promise<string> {
    const response = await fetch(`${this.config.API_BASE_URL}/matches/create.php`, {
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
