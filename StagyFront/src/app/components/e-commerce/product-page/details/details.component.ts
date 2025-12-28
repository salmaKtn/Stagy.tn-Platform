import { Component, OnInit } from '@angular/core';
import { NgbRatingConfig, NgbRating } from '@ng-bootstrap/ng-bootstrap';
import { FormsModule } from '@angular/forms';
import { RouterLink } from '@angular/router';
import { UserService } from '../../../../services/user.service';
import { AppService } from '../../../../services/app.service'; 
import { ReactiveFormsModule } from '@angular/forms';
import { ActivatedRoute } from '@angular/router';
import { NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { CommonModule } from '@angular/common';
import { format } from 'date-fns';



@Component({
    selector: 'app-details',
    templateUrl: './details.component.html',
    styleUrls: ['./details.component.scss'],
    imports: [NgbRating, RouterLink, FormsModule, CommonModule]
})




export class DetailsComponent implements OnInit {

    offerId!: number;
    userRole: string = '';


  rating = 4 ;
  constructor(public config: NgbRatingConfig,private route: ActivatedRoute,
  private userService: UserService,
  private appService: AppService,
  private modalService: NgbModal )
   { 
    config.max = 5;
    config.readonly = true;
  }


  ngOnInit(): void {
    const userData = JSON.parse(localStorage.getItem('user') || '{}');
  if (userData && userData.roles) {
    this.userRole = userData.roles[0]; // Or adjust if multiple roles
  }     
        this.offerId = +this.route.snapshot.paramMap.get('id')!;
        console.log(this.offerId);

  }

   applyToOffer(successModal: any) {
    this.userService.getStudent().subscribe({
      next: (studentData) => {
        const studentId = studentData.id;
                console.log(studentId,'this user');


        const application = {
          compatibility: 0.0,
          status: 'pending',
          offer: this.offerId,
          student:studentId,
          timestamp: new Date().toLocaleString('en-GB', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',
        hour12: false
  })
        };

        this.appService.apply(application).subscribe({
          next: () => {
            this.modalService.open(successModal);
          },
          error: (err) => {
            console.error('Application failed:', err);
            alert('Une erreur est survenue lors de la candidature.');
          }
        });
      },
      error: (err) => {
        console.error('Failed to fetch student:', err);
        alert('Impossible de récupérer les informations de l’étudiant.');
      }
    });
  }

}
