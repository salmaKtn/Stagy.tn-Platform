// user.service.ts
import { Injectable } from '@angular/core';
import { jwtDecode } from 'jwt-decode';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';


export interface Company {
  username: string;
  name: string;
  location: string;
  logoURL: string;
  coverURL: string;
  slogan: string;
  about: string;
  taxNumber: string;
  field: string;
  socialMediaURLs: string[];
  agentName: string;
  agentLastname: string;
  agentEmail: string;
  validation: boolean;
}
export interface Student {
  id: number;
  username: string;
  firstName: string;
  lastName: string;
  email: string;
  // add more fields based on your API
}

@Injectable({ providedIn: 'root' })

export class UserService {
    private apiUrl = 'http://127.0.0.1:8000/api';

  constructor(private http: HttpClient) {}

  getDecodedToken(): any | null {
  const token = localStorage.getItem('token');
  if (!token) return null;

  try {
    return jwtDecode(token);
  } catch {
    return null;
  }
}

  getUserRole(): string | null {
    const decoded = this.getDecodedToken();
      return decoded.roles?.[0] || null;  
  }

  isStudent(): boolean {
    return this.getUserRole() === 'ROLE_STUDENT';
  }

  isCompany(): boolean {
    return this.getUserRole() === 'ROLE_COMPANY';
  }
  isAdmin(): boolean {
    return this.getUserRole() === 'ROLE_ADMIN';
  }


  //**                COMPANY                        **/

/*
getCompanyByUsername(): Observable<any> {
  return this.http.get('http://127.0.0.1:8000/api/company/by-username');
}

*/
  getCompanyByID(Id: number): Observable<any> {
    return this.http.get(`${this.apiUrl}/company/by-id/${Id}`);   /*/api/company/by-id/{id}*/
  }
getCompany(): Observable<any> {
  return this.http.get('http://127.0.0.1:8000/api/company/one');
}

getOffersByCompany(): Observable<any> {
  return this.http.get('http://127.0.0.1:8000/api/company/offers');
}
//************************               STUDENT       ************************************** */

getStudent(): Observable<any> {
  return this.http.get('http://127.0.0.1:8000/api/student/byUsername');
}
 getStudentById():Observable<any> {
  return this.http.get('http://127.0.0.1:8000/api/student/{id}');
}
  getUsername():Observable<any> {
  return this.http.get('http://127.0.0.1:8000/api/user');
}  

}
//******************************user*************************************** */
   