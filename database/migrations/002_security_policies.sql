-- Enable RLS
ALTER TABLE aweb_users ENABLE ROW LEVEL SECURITY;
ALTER TABLE aweb_bookings ENABLE ROW LEVEL SECURITY;
ALTER TABLE aweb_hotel_bookings ENABLE ROW LEVEL SECURITY;
ALTER TABLE aweb_ticket_bookings ENABLE ROW LEVEL SECURITY;
ALTER TABLE aweb_educational_resources ENABLE ROW LEVEL SECURITY;
ALTER TABLE aweb_analytics ENABLE ROW LEVEL SECURITY;

-- Create policies
CREATE POLICY "Users can only view their own data"
    ON aweb_users FOR ALL
    USING (auth.uid() = id);

CREATE POLICY "Users can only access their own bookings"
    ON aweb_bookings FOR ALL
    USING (auth.uid() = user_id);

CREATE POLICY "Public can view educational resources"
    ON aweb_educational_resources FOR SELECT
    TO authenticated
    USING (true);

-- Add missing policies for hotel and ticket bookings
CREATE POLICY "Users can only access their hotel bookings"
    ON aweb_hotel_bookings FOR ALL
    USING (
        booking_id IN (
            SELECT id FROM aweb_bookings WHERE user_id = auth.uid()
        )
    );

CREATE POLICY "Users can only access their ticket bookings"
    ON aweb_ticket_bookings FOR ALL
    USING (
        booking_id IN (
            SELECT id FROM aweb_bookings WHERE user_id = auth.uid()
        )
    );

-- Add admin policy for analytics
CREATE POLICY "Only admins can access analytics"
    ON aweb_analytics FOR ALL
    USING (
        auth.uid() IN (
            SELECT id FROM aweb_users WHERE role = 'admin'
        )
    ); 