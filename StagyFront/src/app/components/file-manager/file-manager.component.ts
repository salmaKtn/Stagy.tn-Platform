import { Component, OnInit } from '@angular/core';
import { FileItemsComponent } from './file-items/file-items.component';
import { BreadcrumbComponent } from '../../shared/components/breadcrumb/breadcrumb.component';

@Component({
    selector: 'app-file-manager',
    templateUrl: './file-manager.component.html',
    styleUrls: ['./file-manager.component.scss'],
    imports: [BreadcrumbComponent, FileItemsComponent]
})
export class FileManagerComponent implements OnInit {

  constructor() { }

  ngOnInit(): void {
  }

}
