
SET ON_ERROR_STOP;

-- Never too late to add some indexes :D

CREATE INDEX cc_call_username_ind ON cc_call USING btree (username);
CREATE INDEX cc_call_starttime_ind ON cc_call USING btree (starttime);
CREATE INDEX cc_call_terminatecause_ind ON cc_call USING btree (terminatecause);
CREATE INDEX cc_call_calledstation_ind ON cc_call USING btree (calledstation);


CREATE INDEX cc_card_creationdate_ind ON cc_card USING btree (creationdate);
CREATE INDEX cc_card_username_ind ON cc_card USING btree (username);
