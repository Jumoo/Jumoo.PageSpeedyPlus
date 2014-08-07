CREATE VIEW SpeedyResults_View AS 
	SELECT Sites.Id as SiteId, Sites.Name As Site, Sites.Url, Months.Id as MonthId, Months.Name as Month, Speedy_Result.* FROM SITES
		INNER JOIN Speedy on Speedy.SiteId = Sites.Id
		INNER JOIN Months on Months.Id = Speedy.MonthId
		INNER JOIN Speedy_Result on Speedy_Result.SpeedyID = Speedy.Id;

CREATE VIEW Checker_View AS
	select Sites.Id, Sites.name, Checker.MonthID, Checker.Status, Checker.Errors from Sites 
		INNER JOIN Checker ON Sites.ID = Checker.SiteId;