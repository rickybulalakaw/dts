<?php 

/* 

View: Track-office-employee

select document.id as 'documentid', track.id as 'trackid', track.creationdate as 'trackdate', track.source as 'tracksource', track.recipientifoffice as 'trackrecipientoffice', track.message as 'message', employeeoffice.employeeid as 'officemember', employeeoffice.id as 'officemembership' from track, office, employee, employeeoffice, document 

where document.id = track.documentid and
track.recipientifoffice = office.id and

office.id = employeeoffice.officeid and 
employee.id = employeeoffice.employeeid and
employee.status = 'Active' and track.status <> 'Acted Upon'

order by documentid asc



View: List of documents open routed to office, with name of document and employee belonging to that office

create view openreceiveddocumentsoffice as 
select distinct track.documentid as "DocumentID", 
document.name as "DocumentName",
track.id as "TrackID", 
track.status as "TrackStatus",
track.creationdate as "TrackDate",
track.recipientifoffice as "TrackRecipientOffice"

from document, track 
where track.documentid = document.id 
and document.status = "Open"
and track.status != "Acted upon"

order by track.creationdate desc


*/

?>