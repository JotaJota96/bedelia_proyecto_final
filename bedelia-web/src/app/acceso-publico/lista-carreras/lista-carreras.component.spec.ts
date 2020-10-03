import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ListaCarrerasComponent } from './lista-carreras.component';

describe('ListaCarrerasComponent', () => {
  let component: ListaCarrerasComponent;
  let fixture: ComponentFixture<ListaCarrerasComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ ListaCarrerasComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(ListaCarrerasComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
