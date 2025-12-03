import { Injectable } from '@angular/core';
import { Config } from '../config';
import { League } from '../entities/league';
import { Match } from '../entities/match';
import { Rating } from '../entities/rating';
import { User } from '../entities/user';
import { fetchWithAuth } from '../utils/http';

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
@Injectable({
  providedIn: 'root',
})
export class MatchesController {
  private config: Config;
  private userHistoryCache: { data: UserHistoryOutput; timestamp: number } | null = null;
  private userHistoryInFlight: Promise<UserHistoryOutput> | null = null;
  private readonly cacheTTL = 15000;

  constructor(config: Config) {
    this.config = config;
  }

  async getGlobalRating(weekly: boolean): Promise<Rating[]> {
    const url = weekly
      ? `${this.config.API_BASE_URL}/matches/global-rating.php`
      : `${this.config.API_BASE_URL}/matches/global-rating-weekly.php`;

    const response = await fetchWithAuth(url, {
      method: 'GET',
      headers: { 'Content-Type': 'application/json' },
    });

    const data = await response.json();

    if (!response.ok) {
      throw new Error(data.message);
    }

    return data.data as Rating[];
  }

  async getUserHistory(forceRefresh: boolean = false): Promise<UserHistoryOutput> {
    if (!forceRefresh) {
      if (this.userHistoryCache && Date.now() - this.userHistoryCache.timestamp < this.cacheTTL) {
        return this.userHistoryCache.data;
      }
      if (this.userHistoryInFlight) {
        return this.userHistoryInFlight;
      }
    }

    const promise = (async () => {
      const response = await fetch(`${this.config.API_BASE_URL}/matches/user-history.php`, {
        method: 'GET',
        headers: { 'Content-Type': 'application/json' },
      });

      const data = await response.json();

      if (!response.ok) {
        this.userHistoryInFlight = null;
        throw new Error(data.message);
      }

      const result = data as UserHistoryOutput;
      this.userHistoryCache = { data: result, timestamp: Date.now() };
      this.userHistoryInFlight = null;
      return result;
    })();

    this.userHistoryInFlight = promise;
    return promise;
  }

  async create(input: Match & { leagueId: number | null }): Promise<string> {
    const response = await fetchWithAuth(`${this.config.API_BASE_URL}/matches/create.php`, {
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
